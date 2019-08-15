<?php


namespace Core\Contracts\Service;

use Core\Components\Cache;
use Core\Components\Helper;
use Core\Contracts\HandlerInterface;

abstract class ActionAbstract
{
    protected $handler;
    protected $data;
    protected $responseId;
    protected $cacheHelper;
    protected $helper;

    public function __construct(HandlerInterface $handler, array $data, $fd = null) {
        $this->handler = $handler;
        $this->data = $data;
        $this->responseId = $fd;
        $this->cacheHelper = new Cache();
        $this->helper = new Helper();
    }
}