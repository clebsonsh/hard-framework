<?php

declare(strict_types=1);

namespace Infra\Contracts;

use Infra\Http\Request;
use Infra\Http\Response;

interface HandlerContract
{
    public function handle(Request $request): Response;
}
