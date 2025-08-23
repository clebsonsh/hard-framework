<?php

declare(strict_types=1);

namespace Tests\Feature\Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Http\Router;

describe('Not Found', function () {
    it('should return an html not found response for web requests', function () {
        $request = new Request('/not-found', HttpMethod::GET, []);
        $router = new Router($request);

        $response = $router->handleRequest();

        expect($response)->toBeInstanceOf(Response::class)
            ->and($response->getBody())->toBe('<h1>404 Not Found</h1>')
            ->and($response->getStatus())->toBe(404);
    });

    it('should return a json not found response for api requests', function () {
        $request = new Request('/api/not-found', HttpMethod::GET, []);
        $router = new Router($request);

        $response = $router->handleRequest();

        expect($response)->toBeInstanceOf(Response::class)
            ->and($response->getBody())->toBe(json_encode(['error' => 'Not Found']))
            ->and($response->getStatus())->toBe(404);
    });
});
