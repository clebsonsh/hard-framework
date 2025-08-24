<?php

declare(strict_types=1);

use Infra\Enums\HttpMethod;
use Infra\Http\Request;

describe('createFromGlobals', function () {
    afterEach(function () {
        unset($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['HTTP_ACCEPT']);
    });

    it('creates a Request instance from PHP superglobals', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $request = Request::createFromGlobals();

        expect($request)->toBeInstanceOf(Request::class);
    });

    it('extracts headers from PHP superglobals', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['HTTP_ACCEPT'] = 'application/json';

        $request = Request::createFromGlobals();

        expect($request)->toBeInstanceOf(Request::class)
            ->and($request->getHeaders())->toBe(['accept' => 'application/json']);
    });

    it('throws a RuntimeException if REQUEST_METHOD is not defined', function () {
        $_SERVER['REQUEST_URI'] = '/test';

        expect(fn () => Request::createFromGlobals())
            ->toThrow(RuntimeException::class);
    });
});

describe('Getters', function () {
    it('getPath returns the correct request path', function () {
        $request = prepareRequest(path: '/users/1');

        expect($request->getPath())
            ->toBeString()
            ->toBe('/users/1');
    });

    it('getMethod returns the correct request method', function () {
        $request = prepareRequest(method: HttpMethod::POST);

        expect($request->getMethod())
            ->toBeInstanceOf(HttpMethod::class)
            ->toBe(HttpMethod::POST);
    });
});

function prepareRequest(
    string $path = '/test',
    HttpMethod $method = HttpMethod::GET,
    array $data = ['test' => 'data'],
): Request {
    return new Request($path, $method, $data);
}
