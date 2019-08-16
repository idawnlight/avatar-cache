<?php

$config = [
    'core' => [
        "debug" => false,
        'handler' => [
            'type' => \Core\HttpHandler\Swoole::class,
            'options' => [
                // work for swoole or anything like it
                'listen' => '0.0.0.0',
                'port' => 9501,
                'config' => [ // Refer to https://wiki.swoole.com/wiki/page/274.html
                    "daemonize" => 0,
                    "worker_num" => \Core\Components\Config::cpuNum() * 4,
                    "max_request" => 128
                ]
            ]
        ],
        'cache' => [
            // PSR-6 CacheItemPoolInterface
            'any' => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 2,
                'path' => CACHE_DIR
            ])),
            'meta' => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 2,
                'path' => CACHE_DIR . 'meta/'
            ])),
            'data' => new \Stash\Pool(new \Stash\Driver\FileSystem([
                'dirSplit' => 2,
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
            // seem to be useless :(
        ]
    ]
];

// Register Error Handler for master process
(new \Whoops\Run)->prependHandler(new \Whoops\Handler\PlainTextHandler())->register();

return $config;
