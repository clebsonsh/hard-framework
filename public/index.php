<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../routes/api.php';
require_once __DIR__.'/../routes/web.php';

use App\Infra\Route;

$router = Route::getInstance();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->handleRequest($path);
