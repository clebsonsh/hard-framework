<?php

declare(strict_types=1);

header('Content-Type: application/json');

use App\Infra\Request;
use App\Infra\Route;

Route::get('/api/test', function () {
    echo json_encode(['test' => 123]);
});

Route::post('/api/post', function(Request $request){
    echo $request;
});
