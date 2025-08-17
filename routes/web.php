<?php

declare(strict_types=1);

use App\Infra\Route;

Route::get('/', function () {
    echo '<h1>Home</h1>';
});

Route::get('/about', function () {
    echo '<h1>About</h1>';
});
