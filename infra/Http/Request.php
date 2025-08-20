<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;

readonly class Request
{
    /**
     * @param  array<string, string>  $data
     */
    public function __construct(
        private string $path,
        private HttpMethod $httpMethod,
        //  @todo deal with routeParams private array $routeParams,

        /**
         * @todo deal with queryParams/queryString private array $getRequestData,
         * @todo deal with formPost private array $postRequestData,
         * @todo for now they are bundled together in data. I'm sure is not a good way to deal with it
         */
        private array $data,
    ) {}

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

    /** @return  mixed[] */
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
}
