<?php


namespace Core\Items;


class AvatarItem extends CacheItem
{
    protected $type = 'avatar';
    protected $info;

    public function __construct($type, $content, $expireAt) {
        parent::__construct($type, $content, $expireAt);

    }
}