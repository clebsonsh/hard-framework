<?php

declare(strict_types=1);

namespace Infra\Http;

readonly class Response
{
    public function __construct(
        private int $status = 200,
        private string $body = '',
        /** @var array<string,string> */
        private array $headers = []
    ) {}

    /** @param mixed[] $data */
    public static function json(array $data, int $status = 200): self
    {
        return new self(
            status: $status,
            body: json_encode($data) ?: 'null',
            headers: ['Content-Type' => 'application/json']
        );
    }

    public static function html(string $html, int $status = 200): self
    {
        return new self(
            status: $status,
            body: $html,
            headers: ['Content-Type' => 'text/html; charset=UTF-8']
        );
    }

    public static function redirect(string $to, int $status = 302): self
    {
        return new self(
            status: $status,
            headers: ['Location' => $to]
        );
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /** @return  string[] */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
