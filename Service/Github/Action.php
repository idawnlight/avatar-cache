<?php

namespace Service\Github;

use Core\Components\Cache;
use Core\Contracts\Service\ActionAbstract;

class Action extends ActionAbstract
{
    public function username() {
        Lib::$type = Lib::TYPE_USERNAME;
        $this->para = Lib::parseData($this->para);
        $key = Cache::generateKey($this->para, 'github_username');
        $url = Lib::buildUrl($this->para);
        $this->handle($key, $url);
    }

    public function id() {
        Lib::$type = Lib::TYPE_ID;
        $this->para = Lib::parseData($this->para);
        $key = Cache::generateKey($this->para, 'github_id');
        $url = Lib::buildUrl($this->para);
        $this->handle($key, $url);
    }
}