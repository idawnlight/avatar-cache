<?php


namespace Service\Github;

use Core\Contracts\Service\BootstrapInterface;
use Core\Router;

class Bootstrap implements BootstrapInterface
{
    public static function route(Router $router) {
        $router->addRoute('GET', '/github/{identifier:[a-z\A-Z\d](?:[a-z\A-Z\d]|-(?=[a-z\A-Z\d])){0,38}}[/]', 'username');
        $router->addRoute('GET', '/github/u/{identifier:[1-9][0-9]{0,11}}[/]', 'id');
    }
}