<?php

namespace Core\Components;

use Stash\Interfaces\PoolInterface;

class Config
{
    protected static $config = [];

    /**
     * @return array
     */
    public static function getConfig() :array {
        return self::$config;
    }

    /**
     * @param $config
     */
    public static function setConfig($config) :void {
        self::$config = $config;
    }

    public static function currentGitCommit(string $git_dir): string {
        if ( is_dir($git_dir) && $hash = file_get_contents($git_dir . 'refs/heads/master') ) {
            return '-' . substr(trim($hash), 0, 6);
        } else {
            return '';
        }
    }

    /**
     * @return int
     */
    public static function cpuNum(): int {
        if (!function_exists("swoole_cpu_num")) {
            return 1;
        } else {
            return swoole_cpu_num();
        }
    }

    /**
     * @return bool
     */
    public static function debug(): bool {
        return self::$config['core']['debug'];
    }

    /**
     * @return string
     */
    public static function version(): string {
        return self::$config['core']['version'] ?? 'unknown';
    }

    /**
     * @return string
     */
    public static function node(): string {
        return self::$config['core']['node'] ?? 'node';
    }

    public static function domain() {
        return self::$config['core']['domain'] ?? '/';
    }

    /**
     * @param string $service
     * @return array
     */
    public static function service(string $service) :array {
        return self::$config['service'][$service] ?? [];
    }

    /**
     * @return string
     */
    public static function handlerType() :string {
        return self::$config['core']['handler']['type'];
    }

    /**
     * @return array
     */
    public static function handlerOptions() :array {
        return self::$config['core']['handler']['options'] ?? [];
    }

    /**
     * @return int
     */
    public static function metaExpire(): int {
        return self::$config['service']['general']['expire']['meta'];
    }

    /**
     * @return int
     */
    public static function dataExpire(): int {
        return self::$config['service']['general']['expire']['data'];
    }

    /**
     * @param $type
     * @return int
     */
    public static function expire($type): int {
        switch ($type) {
            case Cache::TYPE_META:
                return self::metaExpire();
            case Cache::TYPE_DATA:
                return self::dataExpire();
            default:
                return Config::metaExpire();
        }
    }

    /**
     * @return PoolInterface
     */
    public static function anyPool() :PoolInterface {
        return self::$config['core']['cache']['any'];
    }

    /**
     * @return PoolInterface
     */
    public static function metaPool() :PoolInterface {
        return self::$config['core']['cache']['meta'];
    }

    /**
     * @return PoolInterface
     */
    public static function dataPool() :PoolInterface {
        return self::$config['core']['cache']['data'];
    }
}