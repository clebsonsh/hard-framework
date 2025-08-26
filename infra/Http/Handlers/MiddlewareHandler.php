<?php

namespace Infra\Http\Handlers;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\MiddlewareInterface;
use Infra\Interfaces\RequestHandlerInterface;

readonly class MiddlewareHandler implements RequestHandlerInterface
{
    public function __construct(
        private MiddlewareInterface $middleware,
        private RequestHandlerInterface $handler
    ) {}

    public function handle(Request $request): Response
    {
        return $this->middleware->process($request, $this->handler);
    }
}
