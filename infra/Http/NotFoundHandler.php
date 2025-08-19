<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Interfaces\RequestHandlerInterface;

class NotFoundHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return Response::json(['error' => 'Not Found'], 404);
    }
}
