<?php

return [
    'name' => 'App',
    'database' => [
        'driver' => env('DB_DRIVER','sqlite'),
        'mysql' => [
            'DB_server'     => env( 'DB_HOST','127.0.0.1'),
            'DB_port'       => env('DB_PORT', '3306'),
            'DB_user'       => env('DB_USER','root'),
            'DB_pass'       => env('DB_PASSWORD',''),
            'DB_database'   => env('DB_NAME',''),
        ],
        'sqlite' => [
            'DB_file'       => env('DB_FILE',''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers' => [
        \Phpdominicana\Lightwave\Providers\AppServiceProvider::class,
    ],
];
