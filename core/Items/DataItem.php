<?php

namespace Core\Items;

use Core\Components\Config;
use Core\Contracts\CacheAbstract;
use Core\Contracts\Responsible;
use GuzzleHttp\Psr7\Response;

class DataItem extends CacheAbstract implements Responsible
{
    protected $type = 'data';
    protected $content;
    protected $mime;
    protected $size;
    protected $last_modify;

    public function __construct($content = '', $expireAt = -1, $mime = 'image/jpeg', $last_modify = null) {
        $this->content = $content;
        $this->expireAt = $expireAt;
        $this->mime = $mime;
        $this->size = strlen($content);
        $this->last_modify = ($last_modify === null) ? time() : $last_modify;
    }

    public function createResponse(): Response {
        return new Response(200, [
            'Content-Type' => $this->mime,
            'Content-Length' => $this->size,
            'Date' => gmdate('D, d M Y H:i:s T', time()),
            'Last-Modified' => gmdate('D, d M Y H:i:s T', $this->last_modify),
            'Expire' => gmdate('D, d M Y H:i:s T', time() + Config::metaExpire()),
            'Cache-Control' => 'max-age=' . Config::metaExpire(),
            'X-Cache-Status' => 'HIT; ' . $this->expireAt . '; ' . (($this->hasExpired()) ? 'Expired; Refresh' : 'Live')
        ], $this->content);
    }
}