<?php

declare(strict_types=1);

namespace App\Services;

class ImageUrlResolver
{
    public function __construct(
        private readonly string $baseUrl
    ) {
    }

    public function resolve(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//', $path) === 1) {
            return $path;
        }

        return rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
