<?php

namespace Service\Auto;

use Core\Contracts\Service\BootstrapInterface;
use Core\Router;

class Bootstrap implements BootstrapInterface
{
    public static function route(Router $router) {
        $router->addRoute('GET', '/auto/{identifier}', 'index');
    }
}