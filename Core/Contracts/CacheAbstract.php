<?php

namespace Core\Contracts;

abstract class CacheAbstract
{
    public $type = '';
    public $expireAt;

    public function getType() {
        return $this->type;
    }

    public function hasExpired() {
        if ($this->expireAt === -1) return false;
        return (time() > $this->expireAt);
    }

    public function renew($time) {
        $this->expireAt = time() + $time;
        return $this;
    }
}