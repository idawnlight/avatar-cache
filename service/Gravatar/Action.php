<?php


namespace Service\Gravatar;

use Core\Contracts\Service\ActionAbstract;
use GuzzleHttp\Psr7\Response;

class Action extends ActionAbstract
{
    public function index() {
        //echo 'hello world!';
        //var_dump($this->data);
        $this->data = Lib::parseData($this->data);
        $key = $this->cacheHelper->generateKey($this->data, 'gravatar');
        if ($this->cacheHelper->isCached($key)) {

        }
        $response = new Response(200, [], 'hello world!');
        $this->handler->response($response, $this->responseId);
    }
}