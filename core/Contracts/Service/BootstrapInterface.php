<?php


namespace Core\Contracts\Service;

use Core\Router;

/**
 * Interface BootstrapInterface
 * @package Core\Contracts\Service
 */
interface BootstrapInterface
{
    public static function route(Router $router);
}