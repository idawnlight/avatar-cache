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

    public function __construct(HandlerInterface $handler, array $para, $fd = null) {
        $this->handler = $handler;
        $this->para = $para;
        $this->responseId = $fd;
    }

    public function handle($key, $url, $last_modified = -1, $etag = '') {
        if (Cache::isCached($key, Cache::TYPE_META)) {
            $cache = Cache::getCache($key, Cache::TYPE_META);
            $dataKey = $cache->getDataKey();
            $data = Cache::getCache($dataKey, Cache::TYPE_DATA);
            $this->handler->response(Helper::createResponseFromCache($data), $this->responseId);
            if ($cache->hasExpired()) {
                $this->refreshCache($key, $url, true);
            }
        } else {
            $this->handler->response(Helper::createRedirectResponse($url), $this->responseId);
            $this->refreshCache($key, $url);
        }
    }

    public function refreshCache($key, $url, $isCached = false) {
        try {
            $result = Helper::request($url);
            $body = $result->getBody()->__toString();
            $dataKey = Cache::generateKey($body);
            if ($isCached && Cache::getCache($key, Cache::TYPE_META)->getDataKey() === $dataKey) {
                Cache::renewExpire($key, Cache::TYPE_META);
                Cache::renewExpire($dataKey, Cache::TYPE_DATA);
            }
            $mime = $result->getHeaderLine('Content-Type');
            $last_modified = $result->getHeaderLine('Last-Modified') ?? gmdate('D, d M Y H:i:s T', time());
            Cache::setCache($dataKey, Cache::TYPE_DATA, new DataItem($body, time() + Config::dataExpire(), $mime, strtotime($last_modified)));
            Cache::setCache($key, Cache::TYPE_META, new MetaItem($dataKey, $url, $this->para, time() + Config::metaExpire()));
        } catch (GuzzleException $e) {

        }
    }
}