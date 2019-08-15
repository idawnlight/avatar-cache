<?php


namespace Core;

use FastRoute\Dispatcher;
use Core\Components\Config;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Core\Exceptions\HttpHandlerException;

class Bootstrap
{
    protected $handler;
    protected $router;

    public function __construct() {
        $this->router = new Router();
    }

    public function boot(): void {
        $this->_initHttpHandler();
        $this->handler->run();
    }

    public function handle(RequestInterface $request, $fd = null): void {
        //var_dump(file_get_contents('php://input'));
        //var_dump(explode('HTTP/', $_SERVER['SERVER_PROTOCOL']));
        //var_dump($request->getUri()->getPath());
        //var_dump(urldecode($request->getUri()->getQuery()));
        parse_str($request->getUri()->getQuery(), $parameter);
        //var_dump($request->getHeaders());
        //var_dump($request->getMethod());
        //var_dump($request->getHeaders());
        //var_dump($request->getHeader('caChe-conTrol'));

        //var_dump($this->_router->getDispatcher());
        $routeInfo = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());
        //var_dump($routeInfo);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                $response = new Response(404, [], '404 Not Found');
                $this->handler->response($response, $fd);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $response = new Response(405, [], '405 Method Not Allowed');
                $this->handler->response($response, $fd);
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$name, $action] = explode('.', $handler);
                $class = 'Service\\' . $name . '\Action';
                $service = new $class($this->handler, array_merge($vars, $parameter), $fd);
                call_user_func([$service, $action]);
                // ... call $handler with $vars
                break;
        }
    }

    private function _initHttpHandler() :void {
        $handler = Config::handlerType();
        if (!class_exists($handler)) throw new HttpHandlerException('Target HttpHandler ' . $handler . ' not found');
        $this->handler = new $handler($this);
    }
}