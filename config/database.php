<?php
return [
    'default' => 'pgsql',
    'migrations' => 'migrations',


    'connections' => [
        'pgsql' => [
            'driver'    => 'pgsql',
            'host'      => env('DB_HOST', '192.168.1.129'),
            'port'      => env('DB_PORT', 5432),
            'database'  => env('DB_DATABASE', 'ticketing'),
            'username'  => env('DB_USERNAME', 'johan.wijaya'),
            'password'  => env('DB_PASSWORD', 'T3mas@postgres'),
            'charset'   => env('DB_CHARSET', 'utf8'),
            'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('DB_PREFIX', ''),
            'timezone'  => env('DB_TIMEZONE', '-07:00'),
            'strict'    => env('DB_STRICT_MODE', false),
            'schema'    => 'public'
        ]
    ],

    'redis' => [

        'cluster' => false,
        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],
    
    ],
];