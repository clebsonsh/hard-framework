<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

use Infra\Http\Router;

$router = new Router;

$router->handleRequest();
