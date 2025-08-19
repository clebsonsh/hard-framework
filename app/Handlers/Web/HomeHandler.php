<?php

namespace App\Handlers\Web;

use Infra\Contracts\Handler;
use Infra\Http\Request;

class HomeHandler implements Handler
{
    public function handle(Request $request)
    {
        echo '<h1>Home</h1>';
    }
}
