<?php

namespace Core;

use Core\Components\Config;
use FastRoute\RouteCollector;
use function FastRoute\cachedDispatcher;

class Router
{
    protected $dispatcher;
    private $__routeCollector;
    private $__currentService;

    public function __construct() {
        $this->dispatcher = cachedDispatcher(function (RouteCollector $r) {
            $this->__routeCollector = $r;
            $this->loadService();
        }, [
            'cacheFile' => CACHE_DIR . 'route.' . hash('crc32', (Config::version())) . '.php',
            'cacheDisabled' => Config::debug()
        ]);
    }

    protected function loadService() {
        $services = array_diff(scandir(SERVICE_DIR), array(
            '..',
            '.'
        ));
        foreach ($services as $service) {
            if (!is_dir(SERVICE_DIR . $service)) continue;
            $this->__currentService = $service;
            $class = 'Service\\' . $service . '\Bootstrap';
            if (!class_exists($class)) throw new \Exception('Target bootstrap of service ' . $service . '  not found');
            call_user_func($class . '::route', $this);
        }
    }

    public function addRoute($httpMethod, $route, $handler, $r = null) {
        if ($r instanceof RouteCollector) {
            $r->addRoute($httpMethod, $route, $this->__currentService . '.' . $handler);
        } else {
            $this->__routeCollector->addRoute($httpMethod, $route, $this->__currentService . '.' . $handler);
        }
    }

    public function dispatch(string $httpMethod, string $uri): array {
        return $this->dispatcher->dispatch($httpMethod, $uri);
    }
}