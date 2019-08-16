<?php


namespace Core\Contracts\Service;

use Core\Components\Cache;
use Core\Components\Config;
use Core\Components\Helper;
use Core\Contracts\HandlerInterface;
use Core\Items\DataItem;
use Core\Items\MetaItem;
use GuzzleHttp\Exception\GuzzleException;

abstract class ActionAbstract
{
    protected $handler;
    protected $para;
    protected $responseId;
    protected $cache;
    protected $helper;

    public function __construct(HandlerInterface $handler, array $para, $fd = null) {
        $this->handler = $handler;
        $this->para = $para;
        $this->responseId = $fd;
        $this->cache = new Cache();
        $this->helper = new Helper();
    }

    public function refreshCache($key, $url, $isCached = false) {
        try {
            $result = $this->helper->request($url);
            $body = $result->getBody()->__toString();
            $dataKey = $this->cache->generateKey($body);
            if ($isCached && $this->cache->getCache($key, Cache::TYPE_META)->getDataKey() === $dataKey) {
                $this->cache->renewExpire($key, Cache::TYPE_META);
                $this->cache->renewExpire($dataKey, Cache::TYPE_DATA);
            }
            $mime = $result->getHeaderLine('Content-Type');
            $last_modified = $result->getHeaderLine('Last-Modified') ?? gmdate('D, d M Y H:i:s T', time());
            $this->cache->setCache($dataKey, Cache::TYPE_DATA, new DataItem($body, time() + Config::dataExpire(), $mime, strtotime($last_modified)));
            $this->cache->setCache($key, Cache::TYPE_META, new MetaItem($dataKey, $url, $this->para, time() + Config::metaExpire()));
        } catch (GuzzleException $e) {

        }
    }
}