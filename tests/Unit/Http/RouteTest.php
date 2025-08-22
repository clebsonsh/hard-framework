<?php

declare(strict_types=1);

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Http\Route;
use Infra\Interfaces\RequestHandlerInterface;

describe('Matching Logic', function () {
    it('should correctly determine if a route matches a given request', function (bool $expected, string $routePath, HttpMethod $routeMethod, string $requestPath, HttpMethod $requestMethod) {
        $handler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response;
            }
        };

        $route = new Route($routePath, $routeMethod, $handler);
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
        $handler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response;
            }
        };

        $route = new Route('/users/{id}', HttpMethod::GET, $handler);
        $request = new Request('/users/42', HttpMethod::GET, []);

        expect($route->match($request))->toBeTrue()
            ->and($route->getParams())->toEqual(['id' => '42']);

        $routeWithMultipleParams = new Route('/posts/{year}/{month}', HttpMethod::GET, $handler);
        $requestWithMultipleParams = new Request('/posts/2023/10', HttpMethod::GET, []);

        expect($routeWithMultipleParams->match($requestWithMultipleParams))->toBeTrue()
            ->and($routeWithMultipleParams->getParams())->toEqual(['year' => '2023', 'month' => '10']);
    });

    it('should not match routes with parameters if the path does not conform', function () {
        $handler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response;
            }
        };

        $route = new Route('/users/{id}', HttpMethod::GET, $handler);
        $request = new Request('/users', HttpMethod::GET, []); // Missing ID

        expect($route->match($request))->toBeFalse();
    });
});

describe('Handler Accessor', function () {
    it('should return the correct handler instance', function () {
        $handler = new class implements RequestHandlerInterface
        {
            public function handle(Request $request): Response
            {
                return new Response(200, [], 'Handled');
            }
        };

        $route = new Route('/test', HttpMethod::GET, $handler);

        expect($route->getHandler())->toBe($handler);
    });
});
