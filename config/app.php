<?php

declare(strict_types=1);

return [
    'name' => 'Blogy',
    'env' => env('APP_ENV', 'local'),
    'debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOL),
    'url' => env('APP_URL', 'http://localhost:8080'),
];

