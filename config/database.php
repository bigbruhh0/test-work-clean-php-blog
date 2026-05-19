<?php

declare(strict_types=1);

return [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'mysql'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'blog'),
    'username' => env('DB_USERNAME', 'blog'),
    'password' => env('DB_PASSWORD', 'blog'),
    'charset' => 'utf8mb4',
];

