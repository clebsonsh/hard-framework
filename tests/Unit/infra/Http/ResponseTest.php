<?php

declare(strict_types=1);

use Infra\Http\Response;

describe('Constructor', function () {
    it('should create a response with default values', function () {
        $response = new Response;

        expect($response->getStatus())->toBe(200)
            ->and($response->getHeaders())->toBe([])
            ->and($response->getBody())->toBe('');
    });

    it('should create a response with custom values', function () {
        $response = new Response(
            status: 404,
            body: 'Not Found',
            headers: ['X-Test' => 'true']
        );

        expect($response->getStatus())->toBe(404)
            ->and($response->getHeaders())->toBe(['X-Test' => 'true'])
            ->and($response->getBody())->toBe('Not Found');
    });
});

describe('JSON Factory', function () {
    it('should create a JSON response with a 200 status by default', function () {
        $data = ['user' => 'John Doe', 'id' => 123];
        $response = Response::json($data);

        expect($response->getStatus())->toBe(200)
            ->and($response->getHeaders())->toBe(['Content-Type' => 'application/json'])
            ->and($response->getBody())->toBe(json_encode($data));
    });

    it('should create a JSON response with a custom status', function () {
        $data = ['error' => 'Invalid input'];
        $response = Response::json($data, 422);

        expect($response->getStatus())->toBe(422)
            ->and($response->getHeaders())->toBe(['Content-Type' => 'application/json'])
            ->and($response->getBody())->toBe(json_encode($data));
    });

    it('should handle an empty array for a JSON response', function () {
        $response = Response::json([]);

        expect($response->getBody())->toBe('[]');
    });
});

describe('HTML Factory', function () {
    it('should create an HTML response with a 200 status by default', function () {
        $html = '<h1>Hello, World!</h1>';
        $response = Response::html($html);

        expect($response->getStatus())->toBe(200)
            ->and($response->getHeaders())->toBe(['Content-Type' => 'text/html; charset=UTF-8'])
            ->and($response->getBody())->toBe($html);
    });

    it('should create an HTML response with a custom status', function () {
        $html = '<h1>Unauthorized</h1>';
        $response = Response::html($html, 401);

        expect($response->getStatus())->toBe(401)
            ->and($response->getHeaders())->toBe(['Content-Type' => 'text/html; charset=UTF-8'])
            ->and($response->getBody())->toBe($html);
    });
});
