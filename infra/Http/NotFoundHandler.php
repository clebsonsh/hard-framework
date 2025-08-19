<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Contracts\HandlerContract;

class NotFoundHandler implements HandlerContract
{
    public function handle(Request $request): Response
    {
        return Response::json(['error' => 'Not Found'], 404);
    }
}
