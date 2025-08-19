<?php

declare(strict_types=1);

namespace App\Handlers\Web;

use Infra\Interfaces\RequestHandlerInterface;
use Infra\Http\Request;
use Infra\Http\Response;

class AboutHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return Response::html('<h1>About</h1>');
    }
}
