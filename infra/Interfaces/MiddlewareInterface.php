<?php

declare(strict_types=1);

namespace Infra\Interfaces;

use Infra\Http\Request;
use Infra\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}
