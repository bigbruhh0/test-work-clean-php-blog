<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    public function __construct(
        private readonly string $content,
        private readonly int $statusCode = 200,
        private readonly array $headers = ['Content-Type' => 'text/html; charset=utf-8']
    ) {
    }

    public static function html(string $content, int $statusCode = 200, array $headers = []): self
    {
        return new self($content, $statusCode, $headers + ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function redirect(string $url): self
    {
        return new self('', 302, ['Location' => $url]);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
