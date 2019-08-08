<?php

$config = [
    'core' => [
        'handler' => [
            'type' => 'swoole',
            'config' => [
                // work for swoole or anything like it
                'listen' => '0.0.0.0',
                'port' => 9501,
                'config' => [ // Refer to https://wiki.swoole.com/wiki/page/274.html
                    'worker_num'       => 8,
                    'max_request'      => 5000,
                    'task_worker_num'  => 8,
                    'task_max_request' => 1000
                ]
            ]
        ],
        'storage' => [
            'meta' => [
                'type' => 'file'
            ],
            'data' => [
                'type' => 'file'
            ]
        ],
        'rate-limit' => [
            // WIP
            'enabled' => false
        ]
    ],
    'service' => [
        'general' => [
            'cache' => [
                // depend on cron job
                'meta' => 86400,
                'data' => 2592000 // if one avatar data hasn't been read for 1 month, it may be deleted to save space
            ]
        ],
        'gravatar' => [
            // Custom config for every single service
        ]
    ]
];

// Register Error Handler
(new \Whoops\Run)->prependHandler(new \Whoops\Handler\PrettyPageHandler)->register();

return $config;