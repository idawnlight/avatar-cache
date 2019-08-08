<?php


namespace Core\Items;


class CacheItem
{
    protected $type;
    protected $rawContent;
    protected $expireAt;

    public function getType() {
        return $this->type;
    }

    public function __construct($type, $rawContent, $expireAt) {
        $this->type = $type;
        $this->rawContent = $rawContent;
        $this->expireAt = $expireAt;
    }
}