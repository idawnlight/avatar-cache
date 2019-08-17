<?php


namespace Core\Components;

use Core\Contracts\CacheAbstract;
use Core\Contracts\Responsible;
use Core\Items\DataItem;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Helper
{
    public static function request(string $url) {
        $client = new Client();
        try {
            return $client->request('GET', $url, [
                'timeout' => 3,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3872.0 Safari/537.36 avatar-cache/' . Config::version(),
                    'Accept' => 'image/*,*/*'
                ]
            ]);
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public static function createResponseFromCache(CacheAbstract $cache, $dataKey, $gzip): ResponseInterface {
        if (! $cache instanceof DataItem) {
            return new Response(500, [],'internal cache error');
        }

        if ($gzip) {
            $content = $cache->content_gz;
            $header['Content-Encoding'] = 'gzip';
        } else {
            $content = $cache->content;
            $header = [];
        }

        return new Response(200, array_merge([
            'Content-Type' => $cache->mime,
            'Content-Length' => $cache->size,
            'Date' => gmdate('D, d M Y H:i:s T', time()),
            'Last-Modified' => gmdate('D, d M Y H:i:s T', $cache->last_modify),
            'Expire' => gmdate('D, d M Y H:i:s T', time() + Config::metaExpire()),
            'Cache-Control' => 'max-age=' . Config::metaExpire(),
            'ETag' => $dataKey,
            'X-Cache-Status' => 'HIT; ' . $cache->expireAt . '; ' . (($cache->hasExpired()) ? 'Expired; Refresh' : 'Live')
        ], $header), $content);
    }

    public static function createCachedResponse(): ResponseInterface {
        return new Response(304, [
            'X-Cache-Status' => 'HIT; Browser Cache'
        ]);
    }

    public static function createRedirectResponse(string $url): ResponseInterface {
        return new Response(302, [
            'Cache-Control' => 'no-cache',
            'Pragma' => 'no-cache',
            'Location' => $url,
            'X-Cache-Status: MISS; Redirected'
        ]);
    }

    public static function gzencode($str) {
        if (Config::enableGzip() && function_exists("gzencode")) {
            return gzencode($str, Config::gzipLevel());
        } else {
            return $str;
        }
    }
}