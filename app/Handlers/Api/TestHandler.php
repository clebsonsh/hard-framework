<?php

namespace App\Handlers\Api;

use Infra\Contracts\Handler;
use Infra\Http\Request;

class TestHandler implements Handler
{
    public function handle(Request $request)
    {
        header('Content-Type: application/json');
        echo json_encode(['test' => 123]);
    }
}
