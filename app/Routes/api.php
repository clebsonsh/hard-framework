<?php

declare(strict_types=1);

use App\Handlers\Api\PostHandler;
use Infra\Http\Router;

return function (Router $router) {
    $router->post('/api/posts/{id}', new PostHandler);
};
