<?php

namespace Core;

use Core\Components\Config;
use Core\Contracts\HandlerInterface;
use Core\Exceptions\HttpHandlerException;
use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class Bootstrap
{
    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * @var Router
     */
    protected Router $router;

    public function __construct() {
        $this->router = new Router();
        if (PHP_SAPI != 'cli') {
            $handler = \Core\HttpHandler\Native::class;
        } else {
            $handler = Config::handlerType();
        }
        if (!class_exists($handler)) throw new HttpHandlerException('Target HttpHandler ' . $handler . ' not found');
        $this->handler = new $handler($this);
        if (!$this->handler instanceof HandlerInterface) throw new HttpHandlerException('Target HttpHandler ' . $handler . ' invalid');
        $this->handler->run();
    }

    public function handle(RequestInterface $request, $fd = null) {
        parse_str($request->getUri()->getQuery(), $parameter);
        $routeInfo = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response = new Response(404, [], '404 Not Found');
                $this->handler->response($response, $fd);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $response = new Response(405, [], '405 Method Not Allowed');
                $this->handler->response($response, $fd);
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [
                    $name,
                    $action
                ] = explode('.', $handler);
                $class = 'Service\\' . $name . '\Action';
                $service = new $class($this->handler, array_merge($vars, $parameter), $request, $fd);
                call_user_func([
                    $service,
                    $action
                ]);
                break;
        }
    }
}