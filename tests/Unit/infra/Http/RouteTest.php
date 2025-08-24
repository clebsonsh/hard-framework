<?php

declare(strict_types=1);

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Route;
use Tests\Doubles\MockRequestHandler;

describe('Matching Logic', function () {
    beforeEach(function () {
        $this->handler = new MockRequestHandler;
    });

    it('should correctly determine if a route matches a given request', function (bool $expected, string $routePath, HttpMethod $routeMethod, string $requestPath, HttpMethod $requestMethod) {
        $route = new Route($routePath, $routeMethod, $this->handler);
        $request = new Request($requestPath, $requestMethod, []);

        expect($route->match($request))->toBe($expected);

    })->with([
        'should match when path and method are identical' => [
            true, '/test', HttpMethod::GET, '/test', HttpMethod::GET,
        ],
        'should not match when path is different' => [
            false, '/test', HttpMethod::GET, '/different-path', HttpMethod::GET,
        ],
        'should not match when method is different' => [
            false, '/test', HttpMethod::GET, '/test', HttpMethod::POST,
        ],
        'should not match when both path and method are different' => [
            false, '/test', HttpMethod::GET, '/different-path', HttpMethod::POST,
        ],
    ]);

    it('should match routes with parameters and extract them', function () {
        $route = new Route('/users/{id}', HttpMethod::GET, $this->handler);
        $request = new Request('/users/42', HttpMethod::GET, []);

        expect($route->match($request))->toBeTrue()
            ->and($route->getParams())->toEqual(['id' => '42']);

        $routeWithMultipleParams = new Route('/posts/{year}/{month}', HttpMethod::GET, $this->handler);
        $requestWithMultipleParams = new Request('/posts/2023/10', HttpMethod::GET, []);

        expect($routeWithMultipleParams->match($requestWithMultipleParams))->toBeTrue()
            ->and($routeWithMultipleParams->getParams())->toEqual(['year' => '2023', 'month' => '10']);
    });

    it('should not match routes with parameters if the path does not conform', function () {
        $route = new Route('/users/{id}', HttpMethod::GET, $this->handler);
        $request = new Request('/users', HttpMethod::GET, []); // Missing ID

        expect($route->match($request))->toBeFalse();
    });
});

describe('Handler Accessor', function () {
    it('should return the correct handler instance', function () {
        $handler = new MockRequestHandler(200, 'Handled');

        $route = new Route('/test', HttpMethod::GET, $handler);

        expect($route->getHandler())->toBe($handler);
    });

    it('accepts handlers as a class instance', function () {
        $route = new Route('/test', HttpMethod::GET, new MockRequestHandler);

        expect($route->getHandler())
            ->toBeInstanceOf(MockRequestHandler::class)
            ->toBeObject();
    });

    it('accepts handlers as a class string', function () {
        $route = new Route('/test', HttpMethod::GET, MockRequestHandler::class);

        expect($route->getHandler())
            ->toBe(MockRequestHandler::class)
            ->toBeString();
    });
});
