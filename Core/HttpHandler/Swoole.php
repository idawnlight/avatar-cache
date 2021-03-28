<?php

namespace Core\HttpHandler;

use Core\Bootstrap;
use Core\Components\Config;
use Core\Contracts\HandlerInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Request as swooleRequest;
use Swoole\Http\Response as swooleResponse;
use Swoole\Http\Server as swooleServer;
use Swoole\Server\Task as swooleTask;

class Swoole implements HandlerInterface
{
    protected Bootstrap $bootstrap;
    protected array $config;
    protected swooleServer $server;

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
        $resp->header('X-Content-Type-Options', 'nosniff');
        $resp->header('X-Node', Config::node());
        $resp->header('X-Powered-By', 'avatar-cache/' . Config::version());
        $stream = $response->getBody();
        if (strlen($stream) !== 0) {
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
        }
        $resp->end($stream->getContents());
    }

    public function run() {
        \Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);
        \GuzzleHttp\DefaultHandler::setDefaultHandler(\Yurun\Util\Swoole\Guzzle\SwooleHandler::class);

        $this->server = new swooleServer($this->config['listen'], $this->config['port'], SWOOLE_BASE);
        $this->server->set($this->config['config']);

        $this->server->on('request', function (swooleRequest $request, swooleResponse $response) {
            $response->detach();
            $method = $request->server['request_method'] ?? '';
            $uri = $request->server['request_uri'] ?? $request->server['path_info'] ?? '/';
            $headers = $request->header ?? [];
            $protocol = explode('HTTP/', $request->server['server_protocol'])[1] ?? '';
            $request = new Request($method, $uri, $headers, $request->rawContent(), $protocol);
            $this->server->task('test ' . $response->fd);
            $this->bootstrap->handle($request, $response->fd);
        });

        $this->server->on('task', function (swooleServer $server, swooleTask $task) {
//            var_dump($task);
        });

        $this->server->on('finish', function (swooleServer $server, int $task_id, $data) {});

        $this->server->start();
    }
}