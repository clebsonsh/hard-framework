<?php

declare(strict_types=1);

namespace App\Handlers\Api;

use Infra\Contracts\HandlerContract;
use Infra\Http\Request;
use Infra\Http\Response;

class TestHandler implements HandlerContract
{
    public function handle(Request $request): Response
    {
        return Response::json(['test' => 123]);
    }
}
