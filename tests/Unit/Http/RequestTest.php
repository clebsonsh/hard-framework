<?php

use Infra\Enums\HttpMethod;
use Infra\Http\Request;

describe('request', function () {
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
