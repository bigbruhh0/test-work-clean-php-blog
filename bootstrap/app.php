<?php

declare(strict_types=1);

use App\Application;

require_once dirname(__DIR__) . '/src/helpers.php';

$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Dependencies are not installed. Run composer install first.";
    exit;
}

require_once $autoloadPath;

return new Application();

