<?php

namespace App\Infra;

use App\Enums\Http;

class Route
{
    public function __construct(
        private readonly string $path,
        private readonly Http $method,
        /** @var callable */
        private $callback
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): Http
    {
        return $this->method;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function matchesPathAndMethod(string $path, Http $method): bool
    {
        return $this->path === $path and $this->method === $method;
    }
}
