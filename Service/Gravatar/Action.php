<?php

namespace Service\Gravatar;

use Core\Components\Cache;
use Core\Contracts\Service\ActionAbstract;

class Action extends ActionAbstract
{
    public function index() {
        $this->para = Lib::parseData($this->para);
        $key = Cache::generateKey($this->para, 'gravatar');
        $url = Lib::buildUrl($this->para);
        $this->handle($key, $url);
    }
}