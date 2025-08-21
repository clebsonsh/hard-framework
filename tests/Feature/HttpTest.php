<?php

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Http\Router;
use Infra\Interfaces\RequestHandlerInterface;

describe('http module', function () {
    it('should return a response', function () {
        $path = '/test';
        $method = HttpMethod::GET;
        $data = [
            'test' => 'data',
        ];

        $request = new Request($path, $method, $data);
        $router = new Router($request);

        $response = Response::json($data);
        $mockHandler = Mockery::mock(RequestHandlerInterface::class);
        $mockHandler->shouldReceive('handle')
            ->andReturn($response);

        $router->get('/test', $mockHandler);

        $response = $router->handleRequest();

        expect($router)->toBeInstanceOf(Router::class)
            ->and($request)->toBeInstanceOf(Request::class)
            ->and($request->toArray())->toBe($data)
            ->and($response)->toBeInstanceOf(Response::class);
    });
});
