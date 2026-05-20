<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly array $query = [],
        private readonly array $post = [],
        private readonly array $files = [],
        private array $routeParameters = []
    ) {
    }

    public static function capture(): self
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            $path,
            $_GET,
            $_POST,
            $_FILES
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function route(string $key, mixed $default = null): mixed
    {
        return $this->routeParameters[$key] ?? $default;
    }

    public function withRouteParameters(array $routeParameters): self
    {
        $clone = clone $this;
        $clone->routeParameters = $routeParameters;

        return $clone;
    }
}
