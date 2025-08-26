<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Http\Handlers\MiddlewareHandler;
use Infra\Interfaces\MiddlewareInterface;
use Infra\Interfaces\RequestHandlerInterface;

readonly class MiddlewareDispatcher
{
    /** @param  class-string<MiddlewareInterface>[]|MiddlewareInterface[] $middlewares */
    public function __construct(
        private RequestHandlerInterface $routeHandler,
        private array $middlewares
    ) {}

    public function dispatch(Request $request): Response
    {
        $handler = $this->routeHandler;

        foreach (array_reverse($this->middlewares) as $middleware) {
            if (is_string($middleware)) {
                $middleware = new $middleware;
            }

            $handler = new MiddlewareHandler($middleware, $handler);
        }

        return $handler->handle($request);
    }
}
