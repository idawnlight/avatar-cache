<?php

namespace Core\Components;

use Core\Contracts\CacheAbstract;
use Core\Items\DataItem;
use Core\Items\MetaItem;
use Stash\Interfaces\PoolInterface;

class Cache
{
    const TYPE_ANY = 0000;
    const TYPE_DATA = 0001;
    const TYPE_META = 0002;

    public function setCache($identifier, $type = self::TYPE_ANY, $content = '') {
        return self::getPool($type)->save(self::getPool($type)->getItem($identifier)->set($content));
    }

    public function getCache($identifier, $type = self::TYPE_ANY): CacheAbstract {
        if ($type === self::TYPE_DATA) {
            $item = self::getPool($type)->getItem($identifier);
            $cache = $item->get()->renew(Config::dataExpire());
            $item->set($cache);
            self::getPool($type)->save($item);
            return $cache;
        } else {
            return self::getPool($type)->getItem($identifier)->get();
        }
    }

    public function isCached($identifier, $type = self::TYPE_ANY): bool {
        return self::getPool($type)->hasItem($identifier);
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

    public static function getPool($type = self::TYPE_ANY): PoolInterface {
        switch ($type) {
            case self::TYPE_META:
                return Config::metaPool();
            case self::TYPE_DATA:
                return Config::dataPool();
            default:
                return Config::anyPool();
        }
    }
}