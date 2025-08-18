<?php

declare(strict_types=1);

namespace App\Infra;

class Request
{
    private static Request $instance;

    /**
     * @param  mixed[]  $data
     */
    private function __construct(private readonly array $data) {}

    public static function init(): Request
    {
        if (! isset(self::$instance)) {
            $requestData = (array) $_REQUEST;
            $json = file_get_contents('php://input') ?: '';
            $jsonData = (array) json_decode($json, true);
            $data = array_merge($requestData, $jsonData);

            self::$instance = new self($data);
        }

        return self::$instance;
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
