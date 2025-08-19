<?php

declare(strict_types=1);

namespace Infra\Http;

class Response
{
    public function __construct(
        public int $status = 200,
        /** @var array<string,string> */
        public array $headers = [],
        public string $body = ''
    ) {}

    /** @param mixed[] $data */
    public static function json(array $data, int $status = 200): self
    {
        return new self($status, ['Content-Type' => 'application/json'], json_encode($data) ?: 'null');
    }

    public static function html(string $html, int $status = 200): self
    {
        return new self($status, ['Content-Type' => 'text/html; charset=UTF-8'], $html);
    }
}
