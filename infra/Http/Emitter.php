<?php

declare(strict_types=1);

namespace Infra\Http;

class Emitter
{
    public function emit(Response $response): void
    {
        http_response_code($response->getStatus());

        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        echo $response->getBody();
    }
}
