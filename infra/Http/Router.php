<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Exceptions\NotFoundException;
use Infra\Interfaces\RequestHandlerInterface;
use RuntimeException;

class Router
{
    /** @var Route[] */
    private static array $routes = [];

    private Request $request;

    /**
     * @todo handle not authorized requests
     * @todo handle method not allowed request
     */
    public function __construct()
    {
        $this->request = new Request(
            path: $this->getPath(),
            httpMethod: $this->detectHttpMethod(),
            data: $this->getData(),
        );
    }

    private function detectHttpMethod(): HttpMethod
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
    public function getData(): array
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

    public function getPath(): string
    {
        /** @var string $uri */
        $uri = $_SERVER['REQUEST_URI'];

        /** @var string $path */
        $path = parse_url($uri, PHP_URL_PATH);

        return $path;
    }

    public static function get(string $path, RequestHandlerInterface $handler): void
    {
        self::register($path, HttpMethod::GET, $handler);
    }

    public static function post(string $path, RequestHandlerInterface $handler): void
    {
        self::register($path, HttpMethod::POST, $handler);
    }

    public static function put(string $path, RequestHandlerInterface $handler): void
    {
        self::register($path, HttpMethod::PUT, $handler);
    }

    public static function patch(string $path, RequestHandlerInterface $handler): void
    {
        self::register($path, HttpMethod::PATCH, $handler);
    }

    public static function delete(string $path, RequestHandlerInterface $handler): void
    {
        self::register($path, HttpMethod::DELETE, $handler);
    }

    private static function register(string $path, HttpMethod $httpMethod, RequestHandlerInterface $handler): void
    {
        self::$routes[] = new Route($path, $httpMethod, $handler);
    }

    public function handleRequest(): void
    {
        try {
            $response = $this->getRoute()->handle($this->request);
        } catch (NotFoundException) {
            $response = (new NotFoundHandler)->handle($this->request);
        }

        $this->handleResponse($response);
    }

    /**
     * @throws NotFoundException
     */
    private function getRoute(): RequestHandlerInterface
    {
        foreach (self::$routes as $route) {
            if ($route->match($this->request)) {
                return $route->getHandler();
            }
        }

        throw new NotFoundException;
    }

    public function handleResponse(Response $response): void
    {
        http_response_code($response->status);
        foreach ($response->headers as $k => $v) {
            header("$k: $v");
        }
        echo $response->body;
    }
}
