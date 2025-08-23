<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Interfaces\RequestHandlerInterface;

class Route
{
    /** @var array<string,float|int|string> */
    private array $params;

    private string $pattern;

    public function __construct(
        private readonly string $path,
        private readonly HttpMethod $method,
        private readonly RequestHandlerInterface $handler
    ) {
        $this->params = [];
        $this->pattern = $this->compilePath();
    }

    private function compilePath(): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?P<$1>[^/]+)', $this->path);

        return '#^'.$pattern.'$#';
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

        if (preg_match($this->pattern, $request->getPath(), $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->params[$key] = $this->parseValue($value);
                }
            }

            return true;
        }

        return false;
    }

    public function matchPath(Request $request): bool
    {
        return (bool) preg_match($this->pattern, $request->getPath());
    }

    private function parseValue(string $value): float|int|string
    {
        if (filter_var($value, FILTER_VALIDATE_INT) !== false) {
            return (int) $value;
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) !== false) {
            return (float) $value;
        }

        return $value;
    }
}
