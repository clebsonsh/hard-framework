<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Interfaces\RequestHandlerInterface;
use Infra\Enums\HttpMethod;

readonly class Route
{
    public function __construct(
        private string $path,
        private HttpMethod $method,
        private RequestHandlerInterface $handler
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    public function match(string $path, HttpMethod $method): bool
    {
        return $this->path === $path and $this->method === $method;
    }
}
