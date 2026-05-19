<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $this->routes['GET'][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $routes = $this->routes[$request->method()] ?? [];

        foreach ($routes as $route) {
            $matches = $this->match($route['pattern'], $request->path());

            if ($matches === null) {
                continue;
            }

            return $route['handler']($request->withRouteParameters($matches));
        }

        return Response::html(render_not_found(), 404);
    }

    private function match(string $pattern, string $path): ?array
    {
        $parameterNames = [];

        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function (array $matches) use (&$parameterNames): string {
            $parameterNames[] = $matches[1];
            return '([^/]+)';
        }, $pattern);

        if ($regex === null) {
            return null;
        }

        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        array_shift($matches);

        return array_combine($parameterNames, $matches) ?: [];
    }
}

