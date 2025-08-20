<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Interfaces\RequestHandlerInterface;

readonly class Route
{
    public function __construct(
        private string $path,
        private HttpMethod $method,
        private RequestHandlerInterface $handler
    ) {}

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    public function match(Request $request): bool
    {
        return $this->path === $request->getPath() and $this->method === $request->getMethod();
    }
}
