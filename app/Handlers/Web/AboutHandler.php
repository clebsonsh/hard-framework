<?php

declare(strict_types=1);

namespace App\Handlers\Web;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class AboutHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return Response::html('<h1>About</h1>');
    }
}
