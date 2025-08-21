<?php

declare(strict_types=1);

use App\Handlers\Api\PostHandler;
use App\Handlers\Api\TestHandler;
use Infra\Http\Router;

return function (Router $router) {
    $router->get('/api/test', new TestHandler);
    $router->post('/api/post', new PostHandler);
};
