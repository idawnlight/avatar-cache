<?php


namespace Core\HttpHandler;

use Core\Bootstrap;
use Core\Components\Config;
use GuzzleHttp\Psr7\Request;
use Core\Contracts\HandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Server as swooleServer;
use Swoole\Http\Request as swooleRequest;
use Swoole\Http\Response as swooleResponse;

class Swoole implements HandlerInterface
{
    protected $bootstrap;
    protected $config;
    protected $server;

    public function __construct(Bootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
        $this->config = Config::handlerOptions();
    }

    public function response(ResponseInterface $response, $fd = null) {
        $resp = swooleResponse::create($fd);
        $resp->status($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $resp->header($name, $value);
            }
        }
        $stream = $response->getBody();
        if (strlen($stream) !== 0) {
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
            while (!$stream->eof()) {
                $resp->write($stream->read(1024 * 8));
            }
        }
        $resp->end();
    }

    public function run() {
        $this->server = new swooleServer($this->config['listen'], $this->config['port']);
        $this->server->set($this->config['config']);

        $this->server->on('request', function (swooleRequest $request, swooleResponse $response) {
            $response->detach();
            $method = $request->server['request_method'] ?? '';
            $uri = $request->server['request_uri'] ?? $request->server['path_info'] ?? '/';
            $headers = $request->header ?? [];
            $protocol = explode('HTTP/', $request->server['server_protocol'])[1] ?? '';
            $request = new Request($method, $uri, $headers, $request->rawContent(), $protocol);
            $this->bootstrap->handle($request, $response->fd);
        });

        $this->server->start();
    }
}