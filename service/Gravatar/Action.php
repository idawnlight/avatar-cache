<?php

namespace Service\Gravatar;

use Core\Components\Cache;
use Core\Contracts\Service\ActionAbstract;

class Action extends ActionAbstract
{
    public function index() {
        $this->para = Lib::parseData($this->para);
        $key = $this->cache->generateKey($this->para, 'gravatar');
        $url = Lib::buildUrl($this->para);
        if ($this->cache->isCached($key, Cache::TYPE_META)) {
            $cache = $this->cache->getCache($key, Cache::TYPE_META);
            $dataKey = $cache->getDataKey();
            $data = $this->cache->getCache($dataKey, Cache::TYPE_DATA);
            $this->handler->response($data->createResponse(), $this->responseId);
            if ($cache->hasExpired()) {
                $this->refreshCache($key, $url, true);
            }
        } else {
            $this->handler->response($this->helper->createRedirectResponse($url), $this->responseId);
            $this->refreshCache($key, $url);
        }
    }
}