<?php

declare(strict_types=1);

// Register the Composer autoloader...
require_once __DIR__.'/../vendor/autoload.php';

use Infra\Http\Emitter;
use Infra\Http\Request;
use Infra\Http\Router;

try {
    // Create a new Request
    $request = Request::createFromGlobals();

    // Inject Request into the Router
    $router = new Router($request);

    // Load routes
    $apiRoutes = require __DIR__.'/../app/Routes/api.php';
    $webRoutes = require __DIR__.'/../app/Routes/web.php';

    // Register routes into the Router
    $apiRoutes($router);
    $webRoutes($router);

    // Router handle the request and return the response
    $response = $router->handleRequest();

    // Emitter delivery the Response to the client
    (new Emitter)->emit($response);
} catch (Throwable $e) {
    // Case the application crash
    // Log the actual error for the developer
    // @todo Create proper Application logger
    error_log($e->getMessage());

    // Show a generic error to the user
    http_response_code(500);
    echo '<h1>Something went wrong!</h1>';
}
