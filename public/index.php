<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

use App\Infra\Router;

$routes = require_once __DIR__.'/../routes.php';

$router = new Router($routes);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->handleRequest($path);
