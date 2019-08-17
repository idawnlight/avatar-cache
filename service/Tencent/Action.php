<?php

namespace Service\Tencent;

use Core\Components\Cache;
use Core\Contracts\Service\ActionAbstract;

class Action extends ActionAbstract
{
    public function index() {
        $this->para = Lib::parseData($this->para);
        $key = $this->cache->generateKey($this->para, 'qq');
        $url = Lib::buildUrl($this->para);
        $this->handle($key, $url);
    }
}