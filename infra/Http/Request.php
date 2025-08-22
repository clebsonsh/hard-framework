<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use RuntimeException;

class Request
{
    /** @var string[] */
    private array $params;

    /**
     * @param  string[]  $data
     */
    public function __construct(
        private readonly string $path,
        private readonly HttpMethod $httpMethod,
        /**
         * @todo deal with queryParams/queryString private array $getRequestData,
         * @todo deal with formPost private array $postRequestData,
         * @todo for now they are bundled together in data. I'm sure is not a good way to deal with it
         */
        private readonly array $data,
    ) {
        $this->params = [];
    }

    public static function createFromGlobals(): self
    {
        return new self(
            path: self::getUrlPath(),
            httpMethod: self::getHttpMethod(),
            data: self::getData(),
        );
    }

    private static function getHttpMethod(): HttpMethod
    {
        /** @var ?string $requestMethod */
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? null;

        if ($requestMethod === null) {
            throw new RuntimeException('No request method found in $_SERVER');
        }

        return HttpMethod::from(strtolower($requestMethod));
    }

    /**
     * @return string[]
     */
    private static function getData(): array
    {
        // fetch GET and POST data
        $requestData = $_REQUEST;

        // fetch JSON data
        $json = file_get_contents('php://input') ?: '';
        $jsonData = (array) json_decode($json, true);

        /** @var string[] $data */
        $data = array_merge($requestData, $jsonData);

        return $data;
    }

    private static function getUrlPath(): string
    {
        /** @var string $uri */
        $uri = $_SERVER['REQUEST_URI'];

        /** @var string $path */
        $path = parse_url($uri, PHP_URL_PATH);

        return $path;
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
    public function toArray(): array
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

    /** @param  string[]  $params */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    /** @return  string[]  */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }
}
