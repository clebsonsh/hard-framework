<?php

declare(strict_types=1);

use App\Handlers\Web\AboutHandler;
use App\Handlers\Web\HomeHandler;
use Infra\Http\Router;

return function (Router $router) {
    $router->get('/', new HomeHandler);
    $router->get('/about', new AboutHandler);
};
