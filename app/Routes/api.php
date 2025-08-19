<?php

declare(strict_types=1);

use Infra\Http\Request;
use Infra\Http\Router;

Router::get('/api/test', function () {
    echo json_encode(['test' => 123]);
});

Router::post('/api/post', function (Request $request) {
    echo $request;
});
