<?php


namespace Core\Contracts;

use Core\Bootstrap;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface HandlerInterface
 * The HttpHandler
 * @package Core\Contracts
 */
interface HandlerInterface
{
    public function __construct(Bootstrap $callback, array $config = []);

    public function response(ResponseInterface $response, $fd = null);

    public function run();
}