<?php

declare(strict_types=1);

namespace App\Infra;

class Request
{
    private static Request $instance;

    /**
     * @param  array  $data  <string, mixed>
     */
    private function __construct(private readonly array $data) {}

    public static function init(): static
    {
        if (! isset(self::$instance)) {
            $requestData = $_REQUEST ?? [];
            $jsonData = json_decode(file_get_contents('php://input'), true) ?? [];
            $data = array_merge($requestData, $jsonData);

            self::$instance = new static($data);
        }

        return self::$instance;
    }

    public function string($field): string
    {
        return (string) $this->__get($field);
    }

    public function int($field): int
    {
        return (int) $this->__get($field);
    }

    public function __get($field): mixed
    {
        return $this->data[$field] ?? null;
    }

    public function __toString(): string
    {
        return json_encode($this->data);
    }
}
