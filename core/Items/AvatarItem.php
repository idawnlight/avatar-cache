<?php


namespace Core\Items;

use Core\Contracts\Responsible;
use GuzzleHttp\Psr7\Response;

class AvatarItem extends CacheItem implements Responsible
{
    protected $type = 'avatar';
    protected $mime;

    public function __construct($rawContent = '', $expireAt = -1, $mime = 'image/jpeg') {
        parent::__construct($rawContent, $expireAt);
    }

    public function createResponse(): Response {
        // TODO: Implement createResponse() method.
    }
}