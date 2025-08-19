<?php

declare(strict_types=1);

namespace App\Handlers\Web;

use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class HomeHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return view('home', [
            'title' => 'Home',
            'text' => 'This is the home page.',
        ]);
    }
}
