<?php


namespace Service\Gravatar;

use Core\Contracts\Service\BootstrapInterface;
use Core\Router;

class Bootstrap implements BootstrapInterface
{
    public static function route(Router $r) {
        // TODO: Implement route() method.
        //$r->addRoute('GET', '/gravatar', 'index');
        $r->addRoute('GET', '/gravatar/{identifier:[a-f0-9]{32}}', 'index');

    }
}