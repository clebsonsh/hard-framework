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
        $path = '/test';
        $method = HttpMethod::GET;
        $data = [
            'test' => 'data',
        ];

        $request = new Request($path, $method, $data);

        expect($request)->toBeInstanceOf(Request::class);
    });
});
