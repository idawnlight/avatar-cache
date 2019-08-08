<?php

namespace Core\Components;

class Cache
{
    public function writeCache($identifier) {

    }

    public function getCache($identifier) {

    }

    public function isCached($identifier): bool {
        return true;
    }

    public function renewExpire($identifier) {

    }

    public function generateKey($identifier, $salt = null): string {
        if (is_string($identifier)) {
            return md5(trim($identifier) . $salt);
        } else {
            return md5(\GuzzleHttp\json_encode($identifier) . $salt);
        }
    }
}