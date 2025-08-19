<?php

declare(strict_types=1);

namespace Infra;

class Request
{
    /** @var mixed[] */
    private array $data;

    public function __construct()
    {
        $requestData = (array) $_REQUEST;

        $json = file_get_contents('php://input') ?: '';
        $jsonData = (array) json_decode($json, true);

        $this->data = array_merge($requestData, $jsonData);
    }

    public function string(string $field): string
    {
        if (! is_string($this->__get($field))) {
            return '';
        }

        return (string) $this->__get($field);
    }

    public function int(string $field): int
    {
        if (! is_int($this->__get($field))) {
            return 0;
        }

        return (int) $this->__get($field);
    }

    public function __get(string $field): mixed
    {
        return $this->data[$field] ?? null;
    }

    public function __toString(): string
    {
        return json_encode($this->data) ?: '';
    }
}
