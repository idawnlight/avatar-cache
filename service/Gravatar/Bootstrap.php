<?php

namespace Service\Gravatar;

use Core\Contracts\Service\BootstrapInterface;
use Core\Router;

class Bootstrap implements BootstrapInterface
{
    public static function route(Router $router) {
        $router->addRoute('GET', '/gravatar/{identifier:[a-f0-9]{32}}[/]', 'index');
    }
}