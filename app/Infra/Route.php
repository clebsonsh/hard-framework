<?php

namespace App\Infra;

use App\Enums\HttpMethod;

class Route
{
    public function __construct(
        private readonly string     $path,
        private readonly HttpMethod $method,
        /** @var callable */
        private                     $callback
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function matchesPathAndMethod(string $path, HttpMethod $method): bool
    {
        return $this->path === $path and $this->method === $method;
    }
}
