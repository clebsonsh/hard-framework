<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

/** @todo move routes files to a config */
require_once __DIR__.'/../app/Routes/api.php';
require_once __DIR__.'/../app/Routes/web.php';

use Infra\Http\Router;

$router = new Router;

$router->handleRequest();
