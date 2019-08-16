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

    public function id() {
        $this->__type = Lib::TYPE_ID;
        $this->para = Lib::parseData($this->para);
        $key = $this->cache->generateKey($this->para, 'github_id');
        $url = Lib::buildUrl($this->para, Lib::TYPE_ID);
        if ($this->cache->isCached($key, Cache::TYPE_META)) {
            $cache = $this->cache->getCache($key, Cache::TYPE_META);
            $dataKey = $cache->getDataKey();
            $data = $this->cache->getCache($dataKey, Cache::TYPE_DATA);
            $this->handler->response($data->createResponse(), $this->responseId);
            if ($cache->hasExpired()) {
                $this->refreshCache($key, $url);
            }
        } else {
            $this->handler->response($this->helper->createRedirectResponse($url), $this->responseId);
            $this->refreshCache($key, $url);
        }
    }
}