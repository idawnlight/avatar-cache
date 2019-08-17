<?php

namespace Core\HttpHandler;

use Core\Bootstrap;
use Core\Components\Config;
use Core\Contracts\HandlerInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class Native implements HandlerInterface
{
    protected $bootstrap;

    public function __construct(Bootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    public function response(ResponseInterface $response, $fd = null) {
        ignore_user_abort(true);
        $this->send($response);
        flush();
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }

    public function run(): void {
        ob_start();
        $method = $_SERVER['REQUEST_METHOD'] ?? '';
        $uri = $_SERVER['REQUEST_URI'] ?? $_SERVER['PATH_INFO'] ?? '/';
        $headers = getallheaders() ?? [];
        $protocol = explode('HTTP/', $_SERVER['SERVER_PROTOCOL'])[1] ?? '';
        $request = new Request($method, $uri, $headers, file_get_contents('php://input'), $protocol);
        //var_dump($_SERVER);
        $this->bootstrap->handle($request);
    }

    /**
     * Send an HTTP response
     * http-interop/response-sender
     * @param ResponseInterface $response
     * @return void
     */
    function send(ResponseInterface $response)
    {
        $http_line = sprintf('HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );
        header($http_line, true, $response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
        header('X-Content-Type-Options: nosniff');
        header('Server: ' . Config::node());
        header('X-Powered-By: avatar-cache/' . Config::version());
        $stream = $response->getBody();
        if (strlen($stream) !== 0) {
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
            while (!$stream->eof()) {
                echo $stream->read(1024 * 8);
            }
        }
    }
}