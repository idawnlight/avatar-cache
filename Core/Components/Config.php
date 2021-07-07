<?php

namespace Core\Components;

use Stash\Interfaces\PoolInterface;

class Config
{
    protected static array $config = [];

    /**
     * @return array
     */
    public static function getConfig(): array {
        return self::$config;
    }

    /**
     * @param $config
     */
    public static function setConfig($config): void {
        self::$config = $config;
    }

    public static function currentGitCommit(string $git_dir): string {
        if (file_exists(ROOT_DIR . 'commit_hash') && $hash = file_get_contents(ROOT_DIR . 'commit_hash')) {
            return '-' . substr(trim($hash), 0, 7);
        }
        if (is_dir($git_dir) && $hash = file_get_contents($git_dir . 'refs/heads/master')) {
            return '-' . substr(trim($hash), 0, 7);
        } else {
            return '';
        }
    }

    public static function getEnv(string $env, $default = null) {
        return (getenv($env) !== false) ? getenv($env) : $default;
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
    public static function service(string $service): array {
        return self::$config['service'][$service] ?? [];
    }

    /**
     * @return string
     */
    public static function handlerType(): string {
        return self::$config['core']['handler']['type'];
    }

    /**
     * @return array
     */
    public static function handlerOptions(): array {
        return self::$config['core']['handler']['options'] ?? [];
    }

    public static function getLoggerStreams(): array {
        return self::$config['core']['logging']['streams'] ?? [];
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
     * @return PoolInterface
     */
    public static function cachePool() {
        return self::$config['core']['cache'];
    }
}