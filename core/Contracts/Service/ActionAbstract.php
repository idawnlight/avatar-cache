<?php


namespace Core\Contracts\Service;

use Core\Components\Cache;
use Core\Components\Config;
use Core\Components\Helper;
use Core\Contracts\HandlerInterface;
use Core\Items\DataItem;
use Core\Items\MetaItem;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

abstract class ActionAbstract
{
    protected $handler;
    protected $para;
    protected $responseId;
    protected $request;

    public function __construct(HandlerInterface $handler, array $para, Request $request, $fd = null) {
        $this->handler = $handler;
        $this->para = $para;
        $this->request = $request;
        $this->responseId = $fd;
    }

    /**
     * @param string $key
     * @param string $url
     * @return void
     */
    public function handle(string $key, string $url): void {
        $user['modified_since'] = strtotime($this->request->getHeaderLine('If-Modified-Since'));
        $user['etag'] = $this->request->getHeaderLine('If-None-Match');
        if (Cache::isCached($key, Cache::TYPE_META)) {
            $cache = Cache::getCache($key, Cache::TYPE_META);
            $dataKey = $cache->getDataKey();
            $data = Cache::getCache($dataKey, Cache::TYPE_DATA);
            if ('"' . $dataKey . '"' === $user['etag'] || $data->last_modify === $user['modified_since']) {
                $this->handler->response(Helper::createCachedResponse(), $this->responseId);
            } else {
                $this->handler->response(Helper::createResponseFromCache($data, $dataKey), $this->responseId);
            }
            if ($cache->hasExpired()) {
                $this->refreshCache($key, $url, true);
            }
        } else {
            $this->handler->response(Helper::createRedirectResponse($url), $this->responseId);
            $this->refreshCache($key, $url);
        }
    }

    /**
     * @param string $key
     * @param string $url
     * @param bool $isCached
     * @return void
     */
    public function refreshCache(string $key, string $url, bool $isCached = false): void {
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