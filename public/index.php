<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../app/Routes/api.php';
require_once __DIR__.'/../app/Routes/web.php';

use Infra\Http\Router;

/** @var string $uri */
$uri = $_SERVER['REQUEST_URI'];

/** @var string $path */
$path = parse_url($uri, PHP_URL_PATH);

$response = Router::handleRequest($path);

