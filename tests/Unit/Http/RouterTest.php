<?php

declare(strict_types=1);

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Http\Router;
use Infra\Interfaces\RequestHandlerInterface;

describe('Request Handling', function () {
    it('should handle a request and return a response from the correct handler', function () {
        $request = new Request('/found', HttpMethod::GET, []);
        $successHandler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response(status: 200, body: 'Success');
            }
        };
        $router = new Router($request);
        $router->get('/found', $successHandler);

        $response = $router->handleRequest();

        expect($response->status)->toBe(200)
            ->and($response->body)->toBe('Success');
    });

    it('should return a 404 response when no route matches the request URI', function () {
        $request = new Request('/not-found', HttpMethod::GET, []);
        $router = new Router($request);

        $response = $router->handleRequest();

        expect($response->status)->toBe(404);
    });

    it('should return a 404 response when the path matches but the HTTP method does not', function () {
        $request = new Request('/test', HttpMethod::POST, []);
        $router = new Router($request);
        $handler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response(200);
            }
        };
        $router->get('/test', $handler);

        $response = $router->handleRequest();

        expect($response->status)->toBe(404);
    });
});

describe('Route Registration', function () {
    it('should correctly register and handle routes for all HTTP methods', function (HttpMethod $method, string $routerMethod) {
        $request = new Request('/test', $method, []);
        $router = new Router($request);
        $handler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response(status: 201, body: 'Created');
            }
        };

        $router->{$routerMethod}('/test', $handler);
        $response = $router->handleRequest();

        expect($response->status)->toBe(201)
            ->and($response->body)->toBe('Created');
    })->with([
        'GET' => [HttpMethod::GET, 'get'],
        'POST' => [HttpMethod::POST, 'post'],
        'PUT' => [HttpMethod::PUT, 'put'],
        'PATCH' => [HttpMethod::PATCH, 'patch'],
        'DELETE' => [HttpMethod::DELETE, 'delete'],
    ]);
});
