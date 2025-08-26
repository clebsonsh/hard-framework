<?php

declare(strict_types=1);

use App\Handlers\Web\HomeHandler;
use App\Middlewares\LoggingMiddleware;
use Infra\Http\Router;

return function (Router $router) {
    $router->get('/', HomeHandler::class)
        ->addMiddlewares([LoggingMiddleware::class]);
};
