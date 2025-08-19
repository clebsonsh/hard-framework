<?php

declare(strict_types=1);

namespace Infra\Interfaces;

use Infra\Http\Request;
use Infra\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
