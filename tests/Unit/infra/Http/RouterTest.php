<?php

declare(strict_types=1);

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Router;
use Tests\Doubles\MockRequestHandler;

describe('Request Handling', function () {
    beforeEach(function () {
        $this->handler = new MockRequestHandler;
    });

    it('should handle a request and return a response from the correct handler', function () {
        $request = new Request('/found', HttpMethod::GET, []);
        $successHandler = new MockRequestHandler(200, 'Success');
        $router = new Router($request);
        $router->get('/found', $successHandler);

        $response = $router->handleRequest();

        expect($response->getStatus())->toBe(200)
            ->and($response->getBody())->toBe('Success');
    });

    it('should return a 404 response when no route matches the request URI', function () {
        $request = new Request('/not-found', HttpMethod::GET, []);
        $router = new Router($request);

        $response = $router->handleRequest();

        expect($response->getStatus())->toBe(404);
    });

    it('should return a 405 response when the path matches but the HTTP method does not', function () {
        $request = new Request('/test', HttpMethod::POST, []);
        $router = new Router($request);
        $router->get('/test', $this->handler);

        $response = $router->handleRequest();

        expect($response->getStatus())->toBe(405);
    });

    it('should inject route parameters into the request object', function () {
        $request = new Request('/users/123', HttpMethod::GET, []);
        $router = new Router($request);

        $router->get('/users/{id}', $this->handler);
        $router->handleRequest();

        expect($request)->not->toBeNull()
            ->and($request->getParam('id'))->toBe(123)
            ->and($request->getParams())->toBe(['id' => 123]);
    });

    it('should cast route parameter to string', function () {
        $request = new Request('/users/clebson', HttpMethod::GET, []);
        $router = new Router($request);

        $router->get('/users/{id}', $this->handler);
        $router->handleRequest();

        expect($request)->not->toBeNull()
            ->and($request->getParam('id'))->toBe('clebson')
            ->and($request->getParam('id'))->toBeString();
    });

    it('should cast route parameter to integer', function () {
        $request = new Request('/users/42', HttpMethod::GET, []);
        $router = new Router($request);

        $router->get('/users/{id}', $this->handler);
        $router->handleRequest();

        expect($request)->not->toBeNull()
            ->and($request->getParam('id'))->toBe(42)
            ->and($request->getParam('id'))->toBeInt();
    });

    it('should cast route parameter to float', function () {
        $request = new Request('/users/4.2', HttpMethod::GET, []);
        $router = new Router($request);

        $router->get('/users/{id}', $this->handler);
        $router->handleRequest();

        expect($request)->not->toBeNull()
            ->and($request->getParam('id'))->toBe(4.2)
            ->and($request->getParam('id'))->toBeFloat();
    });
});

describe('Route Registration', function () {
    it('should correctly register and handle routes for all HTTP methods', function (HttpMethod $method, string $routerMethod) {
        $request = new Request('/test', $method, []);
        $router = new Router($request);
        $handler = new MockRequestHandler(201, 'Created');

        $router->{$routerMethod}('/test', $handler);
        $response = $router->handleRequest();

        expect($response->getStatus())->toBe(201)
            ->and($response->getBody())->toBe('Created');
    })->with([
        'GET' => [HttpMethod::GET, 'get'],
        'POST' => [HttpMethod::POST, 'post'],
        'PUT' => [HttpMethod::PUT, 'put'],
        'PATCH' => [HttpMethod::PATCH, 'patch'],
        'DELETE' => [HttpMethod::DELETE, 'delete'],
    ]);

    it('should handle a redirect and return a redirect response', function () {
        $request = new Request('/old-path', HttpMethod::GET, []);
        $router = new Router($request);
        $router->redirect('/old-path', '/new-path', 301);

        $response = $router->handleRequest();

        expect($response->getStatus())->toBe(301)
            ->and($response->getHeaders()['Location'])->toBe('/new-path');
    });
});
