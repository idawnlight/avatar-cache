<?php

$config = [
    'core' => [
        'handler' => [
            'type' => \Core\HttpHandler\Swoole::class,
            'options' => [
                // work for swoole or anything like it
                'listen' => '0.0.0.0',
                'port' => 9501,
                'config' => [ // Refer to https://wiki.swoole.com/wiki/page/274.html

                ]
            ]
        ],
        'cache' => [
            // PSR-6 CacheItemPoolInterface
            'meta' => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 3,
                'path' => CACHE_DIR . 'meta/'
            ])),
            'data' => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 3,
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
            ]
        ],
        'gravatar' => [
            // Custom config for every single service
        ]
    ]
];

// Register Error Handler
(new \Whoops\Run)->prependHandler(new \Whoops\Handler\PrettyPageHandler)->register();

// Prepare Stash Pool


return $config;
