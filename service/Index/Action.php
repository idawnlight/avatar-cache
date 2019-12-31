<?php

namespace Service\Index;

use Core\Components\Config;
use Core\Components\Helper;
use Core\Contracts\Service\ActionAbstract;
use Service\Tencent\Lib;

class Action extends ActionAbstract
{
    public function index() {
        $this->handler->response(Helper::createResponseFromString(
            'idawnlight/avatar-cache `' . Config::version() . '` is running on `' . Config::node() . '`'
        ), $this->responseId);
    }
}