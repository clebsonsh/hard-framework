<?php

declare(strict_types=1);

use Infra\Http\Response;

describe('Constructor', function () {
    it('should create a response with default values', function () {
        $response = new Response;

        expect($response->status)->toBe(200)
            ->and($response->headers)->toBe([])
            ->and($response->body)->toBe('');
    });

    it('should create a response with custom values', function () {
        $response = new Response(
            status: 404,
            headers: ['X-Test' => 'true'],
            body: 'Not Found'
        );

        expect($response->status)->toBe(404)
            ->and($response->headers)->toBe(['X-Test' => 'true'])
            ->and($response->body)->toBe('Not Found');
    });
});

describe('JSON Factory', function () {
    it('should create a JSON response with a 200 status by default', function () {
        $data = ['user' => 'John Doe', 'id' => 123];
        $response = Response::json($data);

        expect($response->status)->toBe(200)
            ->and($response->headers)->toBe(['Content-Type' => 'application/json'])
            ->and($response->body)->toBe(json_encode($data));
    });

    it('should create a JSON response with a custom status', function () {
        $data = ['error' => 'Invalid input'];
        $response = Response::json($data, 422);

        expect($response->status)->toBe(422)
            ->and($response->headers)->toBe(['Content-Type' => 'application/json'])
            ->and($response->body)->toBe(json_encode($data));
    });

    it('should handle an empty array for a JSON response', function () {
        $response = Response::json([]);

        expect($response->body)->toBe('[]');
    });
});

describe('HTML Factory', function () {
    it('should create an HTML response with a 200 status by default', function () {
        $html = '<h1>Hello, World!</h1>';
        $response = Response::html($html);

        expect($response->status)->toBe(200)
            ->and($response->headers)->toBe(['Content-Type' => 'text/html; charset=UTF-8'])
            ->and($response->body)->toBe($html);
    });

    it('should create an HTML response with a custom status', function () {
        $html = '<h1>Unauthorized</h1>';
        $response = Response::html($html, 401);

        expect($response->status)->toBe(401)
            ->and($response->headers)->toBe(['Content-Type' => 'text/html; charset=UTF-8'])
            ->and($response->body)->toBe($html);
    });
});
