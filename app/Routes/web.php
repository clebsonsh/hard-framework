<?php

declare(strict_types=1);

use App\Handlers\Web\AboutHandler;
use App\Handlers\Web\HomeHandler;
use Infra\Http\Router;

Router::get('/', new HomeHandler);
Router::get('/about', new AboutHandler);
