<?php

declare(strict_types=1);

use Infra\Http\Router;

Router::get('/', function () {
    echo '<h1>Home</h1>';
});

Router::get('/about', function () {
    echo '<h1>About</h1>';
});
