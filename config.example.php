<?php

use Core\Components\Cache;
use Core\Components\Config;

$cacheDir = Config::getEnv('AVATAR_CONFIG_CACHE_DIR', CACHE_DIR);

$config = [
    'core' => [
        'debug' => false,
        'version' => VERSION . Config::currentGitCommit(GIT_DIR),
        'node' => Config::getEnv('AVATAR_CONFIG_NODE', 'NodeName'),
        'domain' => Config::getEnv('AVATAR_CONFIG_DOMAIN', 'http://avatar.test/'),
        'handler' => [
            'type' => \Core\HttpHandler\Swoole::class,
            'options' => [
                // work for swoole or anything like it
                'listen' => Config::getEnv('AVATAR_CONFIG_HANDLER_LISTEN', '0.0.0.0'),
                'port' => (int) Config::getEnv('AVATAR_CONFIG_HANDLER_PORT', 9000),
                'config' => [ // Refer to https://wiki.swoole.com/wiki/page/274.html
                    "daemonize" => 0,
                    'http_compression' => true,
                    "worker_num" => Config::cpuNum() * 4,
                    "max_request" => 2000
                ]
            ]
        ],
        'cache' => [
            // PSR-6 CacheItemPoolInterface
            Cache::POOL_ANY => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 1,
                'path' => $cacheDir
            ])),
            Cache::POOL_META => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 1,
                'path' => $cacheDir . 'meta/'
            ])),
            Cache::POOL_DATA => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 1,
                'path' => $cacheDir . 'data/'
            ]))
        ],
        'rate-limit' => [
            // WIP
            'enabled' => false
        ]
    ],
    'service' => [
        'general' => [
            'expire' => [
                'meta' => 86400,
                'data' => 2592000
            ]
        ],
        'github' => [
            // Custom config for every single service
            // Example: (actually we don't need it, leave it blank)
            'access_token' => ''
        ]
    ]
];

// Register Error Handler for master process
(new \Whoops\Run)->prependHandler(new \Whoops\Handler\PlainTextHandler())->register();

return $config;
