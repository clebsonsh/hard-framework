<?php

declare(strict_types=1);

namespace App\Infra;

class Router
{
    /**
     * @param  $routes  <string, callable>
     */
    public function __construct(private array $routes) {}

    public function handleRequest(string $path): void
    {
        $callback = $this->routes[$path] ?? $this->routes['/404'];

        $data = Request::init();

        call_user_func($callback, $data);
    }
}
