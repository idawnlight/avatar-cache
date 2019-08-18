<?php

namespace Service\Auto;

use Core\Components\Config;
use Core\Components\Helper;
use Core\Contracts\Service\ActionAbstract;
use Service\Tencent\Lib;

class Action extends ActionAbstract
{
    public function index() {
        $this->para = Lib::parseData($this->para);
        if (filter_var($this->para['identifier'], FILTER_VALIDATE_EMAIL) && strpos($this->para['identifier'], "@qq.com")) {
            $qq = explode("@qq.com", $this->para['identifier'])[0];
            $url = Config::domain() . 'qq/' . $qq . '?s=' . $this->para['size'];
        } else {
            $url = Config::domain() . 'gravatar/'. md5($this->para['identifier']) . "?s=" . $this->para['size'];
        }
        $this->handler->response(Helper::createRedirectResponse($url), $this->responseId);
    }
}