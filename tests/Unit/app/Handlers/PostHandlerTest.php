<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Http\Router;

describe('Api', function () {
    it('returns a JSON response with data and params for a POST request', function () {
        $data = [
            'title' => 'Test title',
            'content' => 'Test content',
        ];
        $params = ['id' => 1.1];

        $request = new Request("/api/posts/{$params['id']}", HttpMethod::POST, $data);
        $router = new Router($request);

        (require 'app/Routes/api.php')($router);

        $response = $router->handleRequest();

        $expected = [
            'data' => $data,
            'params' => $params,
        ];

        expect($response)->toBeInstanceOf(Response::class)
            ->and($response->getBody())->toBe(json_encode($expected))
            ->and($response->getStatus())->toBe(200);
    });
});
