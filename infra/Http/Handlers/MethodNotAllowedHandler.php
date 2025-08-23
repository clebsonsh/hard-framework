<?php

declare(strict_types=1);

namespace Infra\Http\Handlers;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class MethodNotAllowedHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        if ($request->wantsJson()) {
            return Response::json(['error' => 'Method Not Allowed'], 405);
        }

        return Response::html('<h1>405 Method Not Allowed</h1>', 405);
    }
}
