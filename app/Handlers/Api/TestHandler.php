<?php

declare(strict_types=1);

namespace App\Handlers\Api;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class TestHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return Response::json(['test' => 123]);
    }
}
