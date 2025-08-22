<?php

declare(strict_types=1);

use Infra\Http\Emitter;
use Infra\Http\Response;

describe('Emitter', function () {
    it('should emit the status code, headers, and body from a response', function () {
        $response = new Response(
            status: 404,
            headers: [
                'Content-Type' => 'application/json',
                'X-Test' => 'true',
            ],
            body: '{"error":"Not Found"}'
        );
        $emitter = new Emitter;

        ob_start();
        $emitter->emit($response);
        $output = ob_get_clean();

        expect($output)->toBe('{"error":"Not Found"}');
    });

    it('should handle a response with no headers and an empty body', function () {
        $response = new Response(status: 204, headers: [], body: '');
        $emitter = new Emitter;

        ob_start();
        $emitter->emit($response);
        $output = ob_get_clean();

        expect($output)->toBeEmpty();
    });
});
