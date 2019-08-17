<?php

namespace Service\Github;

use Core\Components\Cache;
use Core\Contracts\Service\ActionAbstract;

class Action extends ActionAbstract
{
    private $__type;

    public function username() {
        $this->__type = Lib::TYPE_USERNAME;
        $this->para = Lib::parseData($this->para);
        $key = $this->cache->generateKey($this->para, 'github_username');
        $url = Lib::buildUrl($this->para);
        $this->handle($key, $url);
    }

    public function id() {
        $this->__type = Lib::TYPE_ID;
        $this->para = Lib::parseData($this->para);
        $key = $this->cache->generateKey($this->para, 'github_id');
        $url = Lib::buildUrl($this->para, Lib::TYPE_ID);
        $this->handle($key, $url);
    }
}