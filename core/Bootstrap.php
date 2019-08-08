<?php


namespace Core;

use Core\Exceptions\HttpHandlerException;
use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class Bootstrap
{
    protected $config;
    protected $handler;
    protected $router;

    public function __construct($config) {
        $this->config = $config;
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
                $config = $this->config['service'][$name] ?? [];
                $class = 'Service\\' . $name . '\Action';
                $service = new $class($this->handler, array_merge($vars, $parameter), $config, $fd);
                call_user_func([$service, $action]);
                // ... call $handler with $vars
                break;
        }
    }

    private function _initHttpHandler() :void {
        $handler = 'Core\HttpHandler\\' . ucfirst($this->config['core']['handler']['type'] . 'Handler');
        if (!class_exists($handler)) throw new HttpHandlerException('Target HttpHandler ' . $this->config['core']['handler']['type'] . ' not found');
        $config = $this->config['core']['handler']['config'] ?? [];
        $this->handler = new $handler($this, $config);
    }
}