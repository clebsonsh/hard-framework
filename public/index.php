<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../routes/api.php';
require_once __DIR__.'/../routes/web.php';

use App\Infra\Router;

$router = Router::getInstance();

/** @var string $uri */
$uri = $_SERVER['REQUEST_URI'];

/** @var string $path */
$path = parse_url($uri, PHP_URL_PATH);

$router->handleRequest($path);
