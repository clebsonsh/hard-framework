<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Contracts\HandlerContract;
use Infra\Http\Request;
use Infra\Http\Response;

class NotFoundHandler implements HandlerContract
{
    public function handle(Request $request): Response
    {
        return Response::json($request->toArray());
    }
}
