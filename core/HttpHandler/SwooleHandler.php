<?php


namespace Core\HttpHandler;

use GuzzleHttp\Psr7\Request;
use Swoole;
use Core\Bootstrap;
use Core\Contracts\HandlerInterface;
use Psr\Http\Message\ResponseInterface;

class SwooleHandler implements HandlerInterface
{
    protected $bootstrap;
    protected $config;
    protected $server;

    public function __construct(Bootstrap $bootstrap, array $config = []) {
        $this->bootstrap = $bootstrap;
        $this->config = $config;
    }

    public function response(ResponseInterface $response, $fd = null) {
        $resp = Swoole\Http\Response::create($fd);
        $resp->status($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $resp->header("$name: $value", true);
            }
        }
        $stream = $response->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }
        while (!$stream->eof()) {
            $resp->write($stream->read(1024 * 8));
        }
        $resp->end();
    }

    public function run(): void {
        $this->server = new Swoole\Http\Server($this->config['listen'], $this->config['port']);
        $this->server->set($this->config['config']);

        $this->server->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response) use (&$fd) {
            //var_dump($request->fd);
            //$response->detach();
            //var_dump($request->fd);
            $response->detach();
            //var_dump($request);
            $method = $request->server['request_method'] ?? '';
            $uri = $request->server['request_uri'] ?? $request->server['path_info'] ?? '/';
            $headers = $request->header ?? [];
            $protocol = explode('HTTP/', $request->server['server_protocol'])[1] ?? '';
            $request = new Request($method, $uri, $headers, $request->rawContent(), $protocol);
            $this->bootstrap->handle($request, $response->fd);
            //$this->server->task([$request, $response]);
            //$this->server->task(strval($response->fd));
            //$fd = $response->fd;
            //$response->end("<h1>Hello SwooleHandler. #".rand(1000, 9999)."</h1>");
        });

        $this->server->on('task', function (Swoole\Server $server, $task_id, $from_id, $data) {
            echo "Tasker进程接收到数据";
            echo "#{$server->worker_id}\tonTask: [PID={$server->worker_pid}]: task_id=$task_id.".PHP_EOL;
            var_dump($data);
            [$request, $response] = $data;
            var_dump($request);

            $response->end("<h1>Hello SwooleHandler. #".rand(1000, 9999)."</h1>$from_id");
            echo "async task\n";
        });

        $this->server->start();
    }
}