<?php

namespace Core\Components;

class Config
{
    protected static $config = [];

    public static function getConfig() :array {
        return self::$config;
    }

    public static function setConfig($config) :void {
        self::$config = $config;
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
}