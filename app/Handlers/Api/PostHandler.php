<?php

namespace App\Handlers\Api;

use Infra\Contracts\Handler;
use Infra\Http\Request;

class PostHandler implements Handler
{
    public function handle(Request $request)
    {
        header('Content-Type: application/json');
        echo $request;
    }
}
