<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use RuntimeException;

class Request
{
    /** @var array<string,float|int|string> */
    private array $params;

    /** @var string[] */
    private readonly array $headers;

    /**
     * @param  string[]  $data
     * @param  string[]  $headers
     */
    public function __construct(
        private readonly string $path,
        private readonly HttpMethod $httpMethod,
        private readonly array $data,
        array $headers = []
    ) {
        $this->params = [];
        $this->headers = $headers;
    }

    public static function createFromGlobals(): self
    {
        return new self(
            path: self::getPathFormGlobals(),
            httpMethod: self::getMethodFromGlobals(),
            data: self::getDataFromGlobals(),
            headers: self::getHeadersFromGlobals(),
        );
    }

    private static function getPathFormGlobals(): string
    {
        /** @var string $uri */
        $uri = $_SERVER['REQUEST_URI'];

        /** @var string $path */
        $path = parse_url($uri, PHP_URL_PATH);

        return $path;
    }

    private static function getMethodFromGlobals(): HttpMethod
    {
        /** @var ?string $requestMethod */
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? null;

        if ($requestMethod === null) {
            throw new RuntimeException('No request method found in $_SERVER');
        }

        return HttpMethod::from(strtolower($requestMethod));
    }

    /** @return string[] */
    private static function getDataFromGlobals(): array
    {
        /** @var string[] $getData */
        $getData = $_GET;

        /** @var string[] $postData */
        $postData = $_POST;

        /** @var string[] $jsonData */
        $jsonData = json_decode(file_get_contents('php://input') ?: '', true) ?? [];

        return array_merge($getData, $postData, $jsonData);
    }

    /** @return string[] */
    private static function getHeadersFromGlobals(): array
    {
        /** @var string[] $headers */
        $headers = [];

        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                // Normalizes the header name from 'HTTP_CONTENT_TYPE' to 'content-type'.
                $headerName = str_replace('_', '-', strtolower(substr($name, 5)));

                $headers[$headerName] = $value;
            }
        }

        return $headers;
    }

    public function int(string $field): int
    {
        return (int) $this->__get($field);
    }

    public function bool(string $field): bool
    {
        return (bool) $this->__get($field);
    }

    public function __get(string $field): string
    {
        return $this->data[$field] ?? '';
    }

    /** @return string[] */
    public function getData(): array
    {
        return $this->data;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): HttpMethod
    {
        return $this->httpMethod;
    }

    /** @return string[] */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** @param array<string,float|int|string> $params */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /** @return array<string,float|int|string> */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function wantsJson(): bool
    {
        if (str_starts_with($this->getPath(), '/api/')) {
            return true;
        }

        return str_contains($this->headers['accept'] ?? '', 'application/json');
    }
}
