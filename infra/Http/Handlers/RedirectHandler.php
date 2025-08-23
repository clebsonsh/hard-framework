<?php

declare(strict_types=1);

namespace Infra\Http\Handlers;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

readonly class RedirectHandler implements RequestHandlerInterface
{
    public function __construct(
        private string $to,
        private int $status
    ) {}

    public function handle(Request $request): Response
    {
        return Response::redirect($this->to, $this->status);
    }
}
