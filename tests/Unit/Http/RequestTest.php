<?php

use Infra\Enums\HttpMethod;
use Infra\Http\Request;

describe('request', function () {
    it('should create a request from globals', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $request = Request::createFromGlobals();

        expect($request)->toBeInstanceOf(Request::class);
    });

    it('should not create a request from globals if method is null', function () {
        $_SERVER['REQUEST_METHOD'] = null;
        $_SERVER['REQUEST_URI'] = '/test';

        expect(fn () => Request::createFromGlobals())
            ->toThrow(RuntimeException::class);
    });

    it('should be created', function () {
        $request = prepareRequest();

        expect($request)->toBeInstanceOf(Request::class);
    });

    it('should a data field default type be string ', function () {
        $request = prepareRequest();

        expect($request->test)
            ->toBeString()
            ->toBe('data');
    });

    it('should convert a data field to int ', function () {
        $request = prepareRequest(data: [
            'test' => '123',
        ]);

        expect($request->int('test'))
            ->toBeInt()
            ->toBe(123);
    });

    it('should return request data has array', function () {
        $data = [
            'test' => 'data',
            'data' => 'test',
        ];

        $request = prepareRequest(data: $data);

        expect($request->toArray())
            ->toBeArray()
            ->toBe($data);
    });

    it('should return request path has string', function () {
        $request = prepareRequest();

        expect($request->getPath())
            ->toBeString()
            ->toBe('/test');
    });

    it('should return request method has HttpMethod enum', function () {
        $request = prepareRequest();

        expect($request->getMethod())
            ->toBeInstanceOf(HttpMethod::class)
            ->toBe(HttpMethod::GET);
    });
});

function prepareRequest(
    string $path = '/test',
    HttpMethod $method = HttpMethod::GET,
    array $data = ['test' => 'data'],
): Request {
    return new Request($path, $method, $data);
}
