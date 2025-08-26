<?php

declare(strict_types=1);

namespace App\Middlewares;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\MiddlewareInterface;
use Infra\Interfaces\RequestHandlerInterface;

class LoggingMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        info('Route: '.$request->getPath().' accessed');

        return $handler->handle($request);
    }
}
