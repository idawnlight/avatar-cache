<?php

namespace Service\Tencent;

use Core\Components\Cache;
use Core\Components\Config;
use Core\Contracts\Service\ActionAbstract;
use Core\Items\DataItem;
use Core\Items\MetaItem;
use GuzzleHttp\Exception\GuzzleException;

class Action extends ActionAbstract
{
    public function index() {
        $this->para = Lib::parseData($this->para);
        $key = $this->cache->generateKey($this->para, 'gravatar');
        if ($this->cache->isCached($key, Cache::TYPE_META)) {
            $cache = $this->cache->getCache($key, Cache::TYPE_META);
            $dataKey = $cache->getDataKey();
            $data = $this->cache->getCache($dataKey, Cache::TYPE_DATA);
            $this->handler->response($data->createResponse(), $this->responseId);
            if ($cache->hasExpired()) {
                $this->refreshCache($key);
            }
        } else {
            $url = Lib::buildUrl($this->para);
            $this->handler->response($this->helper->createRedirectResponse($url), $this->responseId);
            $this->refreshCache($key);
        }
    }

    public function refreshCache($key) {
        $url = Lib::buildUrl($this->para);
        try {
            $result = $this->helper->request($url);
            $body = $result->getBody()->__toString();
            $dataKey = $this->cache->generateKey($body);
            $mime = $result->getHeaderLine('Content-Type');
            $last_modified = $result->getHeaderLine('Last-Modified') ?? gmdate('D, d M Y H:i:s T', time());
            $this->cache->setCache($dataKey, Cache::TYPE_DATA, new DataItem($body, time() + Config::dataExpire(), $mime, strtotime($last_modified)));
            $this->cache->setCache($key, Cache::TYPE_META, new MetaItem($dataKey, $url, $this->para, time() + Config::metaExpire()));
        } catch (GuzzleException $e) {

        }
    }
}