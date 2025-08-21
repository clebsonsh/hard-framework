<?php

declare(strict_types=1);

namespace Infra\Http;

class Emitter
{
    public function emit(Response $response): void
    {
        http_response_code($response->status);

        foreach ($response->headers as $name => $value) {
            header("$name: $value");
        }

        echo $response->body;
    }
}
