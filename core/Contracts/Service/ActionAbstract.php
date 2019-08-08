<?php


namespace Core\Contracts\Service;

use Core\Components\Cache;
use Core\Components\Helper;
use Core\Contracts\HandlerInterface;

abstract class ActionAbstract
{
    protected $handler;
    protected $data;
    protected $config;
    protected $responseId;
    protected $cacheHelper;
    protected $helper;

    public function __construct(HandlerInterface $handler, array $data, array $config, $fd = null) {
        $this->handler = $handler;
        $this->data = $data;
        $this->config = $config;
        $this->responseId = $fd;
        $this->cacheHelper = new Cache();
        $this->helper = new Helper();
    }
}