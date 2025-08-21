<?php

use Infra\Enums\HttpMethod;
use Infra\Http\Request;

describe('createFromGlobals', function () {
    afterEach(function () {
        unset($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    });

    it('should create a request from globals', function () {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/test';

        $request = Request::createFromGlobals();

        expect($request)->toBeInstanceOf(Request::class);
    });

    it('should throw an exception if request method is not defined', function () {
        $_SERVER['REQUEST_URI'] = '/test';

        expect(fn () => Request::createFromGlobals())
            ->toThrow(RuntimeException::class);
    });
});

describe('Data Handling', function () {
    beforeEach(function () {
        $this->data = [
            'name' => 'John Doe',
            'age' => '30',
            'active' => 'true',
        ];

        $this->request = prepareRequest(data: $this->data);
    });

    it('should access request data as properties', function () {
        expect($this->request->name)
            ->toBeString()
            ->toBe('John Doe');
    });

    it('should convert a data field to an integer', function () {
        expect($this->request->int('age'))
            ->toBeInt()
            ->toBe(30);
    });

    it('should convert a data field to a boolean', function () {
        expect($this->request->bool('active'))
            ->toBeBool()
            ->toBeTrue();
    });

    it('should return all data as an array', function () {
        expect($this->request->toArray())
            ->toBeArray()
            ->toBe($this->data);
    });
});

it('should return the correct request path', function () {
    $request = prepareRequest(path: '/users/1');

    expect($request->getPath())
        ->toBeString()
        ->toBe('/users/1');
});

it('should return the correct request method', function () {
    $request = prepareRequest(method: HttpMethod::POST);

    expect($request->getMethod())
        ->toBeInstanceOf(HttpMethod::class)
        ->toBe(HttpMethod::POST);
});

function prepareRequest(
    string $path = '/test',
    HttpMethod $method = HttpMethod::GET,
    array $data = ['test' => 'data'],
): Request {
    return new Request($path, $method, $data);
}
