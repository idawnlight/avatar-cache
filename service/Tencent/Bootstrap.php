<?php


namespace Service\Tencent;

use Core\Contracts\Service\BootstrapInterface;
use Core\Router;

class Bootstrap implements BootstrapInterface
{
    public static function route(Router $router) {
        $router->addRoute('GET', '/qq/{identifier:[1-9][0-9]{4,10}}', 'index');
    }
}