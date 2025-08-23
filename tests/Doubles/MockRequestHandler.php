<?php

declare(strict_types=1);

namespace Tests\Doubles;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

readonly class MockRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private int $status = 200,
        private string $body = '',
        private array $headers = []
    ) {}

    public function handle(Request $request): Response
    {
        return new Response($this->status, $this->body, $this->headers);
    }
}
