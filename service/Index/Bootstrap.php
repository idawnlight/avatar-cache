<?php

namespace Service\Index;

use Core\Contracts\Service\BootstrapInterface;
use Core\Router;

class Bootstrap implements BootstrapInterface
{
    public static function route(Router $router) {
        $router->addRoute('GET', '/', 'index');
    }
}