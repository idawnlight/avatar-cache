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

    const POOL_ANY = 0020;
    const POOL_META = 0021;
    const POOL_DATA = 0022;

    /**
     * @param string $identifier
     * @param int $type
     * @param \Serializable | string $content
     * @return bool
     */
    public static function setCache($identifier, $type, $content) {
        return self::getPool($type)->save(self::getPool($type)->getItem($identifier)->set($content));
    }

    /**
     * @param int $type
     * @return PoolInterface
     */
    public static function getPool($type = self::TYPE_ANY): PoolInterface {
        $pool = Config::cachePool();
        if (is_array($pool)) {
            switch ($type) {
                case self::TYPE_META:
                    return $pool[self::POOL_META];
                    break;
                case self::TYPE_DATA:
                    return $pool[self::POOL_DATA];
                    break;
                default:
                    return $pool[self::POOL_ANY];
            }
        } else if ($pool instanceof PoolInterface) {
            switch ($type) {
                case self::TYPE_META:
                    $pool->setNamespace('meta');
                    break;
                case self::TYPE_DATA:
                    $pool->setNamespace('data');
                    break;
                default:
                    $pool->setNamespace(null);
            }
            return $pool;
        }
    }

    /**
     * @param string $identifier
     * @param int $type
     * @return CacheAbstract | MetaItem | DataItem
     */
    public static function getCache(string $identifier, $type = self::TYPE_ANY): CacheAbstract {
        return self::getPool($type)->getItem($identifier)->get();
    }

    /**
     * @param string $identifier
     * @param int $type
     * @return bool
     */
    public static function isCached(string $identifier, $type = self::TYPE_ANY): bool {
        return self::getPool($type)->hasItem($identifier);
    }

    /**
     * @param string $identifier
     * @param int $type
     * @return void
     */
    public static function renewExpire(string $identifier, $type = self::TYPE_ANY): void {
        $item = self::getPool($type)->getItem($identifier);
        if ($item->get() instanceof CacheAbstract) {
            $cache = $item->get()->renew(Config::expire($type));
            $item->set($cache);
            self::getPool($type)->save($item);
        }
    }

    /**
     * @param mixed $identifier
     * @param string $salt
     * @return string
     */
    public static function generateKey($identifier, $salt = null): string {
        if (is_string($identifier)) {
            return md5(trim($identifier) . $salt);
        } else {
            return md5(serialize($identifier) . $salt);
        }
    }
}