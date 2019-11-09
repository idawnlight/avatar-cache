<?php

use Core\Components\Cache;
use Core\Components\Config;

$config = [
    'core' => [
        'debug' => false,
        'version' => VERSION . Config::currentGitCommit(GIT_DIR),
        'node' => 'NodeName',
        'domain' => 'http://avatar.test/',
        'handler' => [
            'type' => \Core\HttpHandler\Swoole::class,
            'options' => [
                // work for swoole or anything like it
                'listen' => '0.0.0.0',
                'port' => 9501,
                'config' => [ // Refer to https://wiki.swoole.com/wiki/page/274.html
                    "daemonize" => 0,
                    "worker_num" => Config::cpuNum() * 4,
                    "max_request" => 128
                ]
            ]
        ],
        'cache' => [
            // PSR-6 CacheItemPoolInterface
            Cache::POOL_ANY => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 1,
                'path' => CACHE_DIR
            ])),
            Cache::POOL_META => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 1,
                'path' => CACHE_DIR . 'meta/'
            ])),
            Cache::POOL_DATA => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 1,
                'path' => CACHE_DIR . 'data/'
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
            ],
            'http-request' => [
                'verify-ssl-certificate': true
            ]
        ],
        'github' => [
            // Custom config for every single service
            // In fact we don't need it
            'access_token' => ''
        ]
    ]
];

// Register Error Handler for master process
(new \Whoops\Run)->prependHandler(new \Whoops\Handler\PlainTextHandler())->register();

return $config;
