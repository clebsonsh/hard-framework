<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Interfaces\RequestHandlerInterface;

class Route
{
    /** @var array<string,float|int|string> */
    private array $params;

    public function __construct(
        private readonly string $path,
        private readonly HttpMethod $method,
        private readonly RequestHandlerInterface $handler
    ) {
        $this->params = [];
    }

    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }

    /** @return  array<string,float|int|string> */
    public function getParams(): array
    {
        return $this->params;
    }

    public function match(Request $request): bool
    {
        if ($this->method !== $request->getMethod()) {
            return false;
        }

        // Convert route path to a regex pattern
        // e.g., /users/{id} becomes #^/users/(?P<id>[^/]+)$#
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?P<$1>[^/]+)', $this->path);
        $pattern = '#^'.$pattern.'$#';

        if (preg_match($pattern, $request->getPath(), $matches)) {
            // Extract named parameters
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->params[$key] = $value;
                }
            }

            return true;
        }

        return false;
    }
}
