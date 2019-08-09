<?php


namespace Core\Items;


class AvatarItem extends CacheItem
{
    protected $type = 'avatar';
    protected $info;

    public function __construct($type, $rawContent, $expireAt) {
        parent::__construct($type, $rawContent, $expireAt);

    }
}