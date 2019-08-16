<?php

namespace Core\Components;

use Stash\Interfaces\PoolInterface;

class Config
{
    protected static $config = [];

    public static function getConfig() :array {
        return self::$config;
    }

    public static function setConfig($config) :void {
        self::$config = $config;
    }

    public static function cpuNum() {
        if (!function_exists("swoole_cpu_num")) {
            return 1;
        } else {
            return swoole_cpu_num();
        }
    }

    public static function debug() {
        return self::$config['core']['debug'];
    }

    public static function service($service) :array {
        return self::$config['service'][$service] ?? [];
    }

    public static function handlerType() :string {
        return self::$config['core']['handler']['type'];
    }

    public static function handlerOptions() :array {
        return self::$config['core']['handler']['options'] ?? [];
    }

    public static function metaExpire() {
        return self::$config['service']['general']['expire']['meta'];
    }

    public static function dataExpire() {
        return self::$config['service']['general']['expire']['data'];
    }

    public static function anyPool() :PoolInterface {
        return self::$config['core']['cache']['any'];
    }

    public static function metaPool() :PoolInterface {
        return self::$config['core']['cache']['meta'];
    }

    public static function dataPool() :PoolInterface {
        return self::$config['core']['cache']['data'];
    }
}