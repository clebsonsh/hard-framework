<?php

declare(strict_types=1);

namespace Infra\Http\Handlers;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class NotFoundHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        if ($request->wantsJson()) {
            return Response::json(['error' => 'Not Found'], 404);
        }

        return Response::html('<h1>404 Not Found</h1>', 404);
    }
}
