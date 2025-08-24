<?php

declare(strict_types=1);

use Infra\Http\Emitter;
use Infra\Http\Response;

describe('Emitter', function () {
    it('emits the status code, headers, and body from a response', function () {
        $response = new Response(
            status: 404,
            body: '{"error":"Not Found"}',
            headers: [
                'Content-Type' => 'application/json',
                'X-Test' => 'true',
            ]
        );
        $emitter = new Emitter;

        ob_start();
        $emitter->emit($response);
        $output = ob_get_clean();

        expect($output)->toBe('{"error":"Not Found"}');
    });

    it('handles a response with no headers and an empty body', function () {
        $response = new Response(status: 204, body: '', headers: []);
        $emitter = new Emitter;

        ob_start();
        $emitter->emit($response);
        $output = ob_get_clean();

        expect($output)->toBeEmpty();
    });
});
