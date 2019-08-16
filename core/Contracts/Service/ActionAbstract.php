<?php


namespace Core\Contracts\Service;

use Core\Components\Cache;
use Core\Components\Helper;
use Core\Contracts\HandlerInterface;

abstract class ActionAbstract
{
    protected $handler;
    protected $para;
    protected $responseId;
    protected $cache;
    protected $helper;

    public function __construct(HandlerInterface $handler, array $para, $fd = null) {
        $this->handler = $handler;
        $this->para = $para;
        $this->responseId = $fd;
        $this->cache = new Cache();
        $this->helper = new Helper();
    }
}