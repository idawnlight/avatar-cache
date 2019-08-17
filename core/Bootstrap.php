<?php


namespace Core;

use Core\Contracts\HandlerInterface;
use FastRoute\Dispatcher;
use Core\Components\Config;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Core\Exceptions\HttpHandlerException;

class Bootstrap
{
    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * @var Router
     */
    protected $router;

    public function __construct() {
        $this->router = new Router();
        if (PHP_SAPI != 'cli') {
            $handler = \Core\HttpHandler\Native::class;
        } else {
            $handler = Config::handlerType();
        }
        if (! class_exists($handler)) throw new HttpHandlerException('Target HttpHandler ' . $handler . ' not found');
        $this->handler = new $handler($this);
        if (! $this->handler instanceof HandlerInterface) throw new HttpHandlerException('Target HttpHandler ' . $handler . ' invalid');
        $this->handler->run();
    }

    public function handle(RequestInterface $request, $fd = null) {
        parse_str($request->getUri()->getQuery(), $parameter);
        $routeInfo = $this->router->dispatch($request->getMethod(), $request->getUri()->getPath());
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
                $service = new $class($this->handler, array_merge($vars, $parameter), $request, $fd);
                call_user_func([$service, $action]);
                // ... call $handler with $vars
                break;
        }
    }
}