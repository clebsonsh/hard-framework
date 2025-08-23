<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use Infra\Enums\HttpMethod;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Http\Router;

describe('Web', function () {
    it('should return home page', function () {
        $request = new Request('/', HttpMethod::GET, []);
        $router = new Router($request);

        (require 'app/Routes/web.php')($router);

        $response = $router->handleRequest();

        expect($response)->toBeInstanceOf(Response::class)
            ->and($response->getBody())->toBe('<h1>Home</h1>')
            ->and($response->getStatus())->toBe(200);
    });
});
