<?php

declare(strict_types=1);

namespace App\Handlers\Web;

use Infra\Contracts\HandlerContract;
use Infra\Http\Request;
use Infra\Http\Response;

class HomeHandler implements HandlerContract
{
    public function handle(Request $request): Response
    {
        return Response::html('<h1>Home</h1>');
    }
}
