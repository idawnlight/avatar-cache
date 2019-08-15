<?php

namespace Core\Items;

class CacheItem
{
    protected $type = '';
    protected $rawContent;
    protected $expireAt;
    protected $size;

    public function getType() {
        return $this->type;
    }

    public function hasExpired() {
        if ($this->expireAt === -1) return false;
        return (time() > $this->expireAt);
    }

    public function renew($time) {
        $this->expireAt = time() + $time;
    }

    public function __construct($rawContent = '', $expireAt = -1) {
        $this->rawContent = $rawContent;
        $this->expireAt = $expireAt;
        $this->size = strlen($rawContent);
    }
}