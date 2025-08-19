<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Contracts\HandlerContract;
use Infra\Enums\HttpMethod;

class Router
{
    /** @var array <int, Route> */
    private static array $routes = [];

    public static function handleRequest(string $path): Response
    {
        /** @var string $requestMethod */
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $httpMethod = HttpMethod::from(strtolower($requestMethod));

        $handler = new NotFoundHandler;

        /** @var Route $route */
        foreach (self::$routes as $route) {
            if ($route->match($path, $httpMethod)) {
                $handler = $route->getHandler();
            }
        }

        return $handler->handle(new Request);
    }

    public static function get(string $path, HandlerContract $handler): void
    {
        self::register($path, HttpMethod::GET, $handler);
    }

    public static function post(string $path, HandlerContract $handler): void
    {
        self::register($path, HttpMethod::POST, $handler);
    }

    public static function put(string $path, HandlerContract $handler): void
    {
        self::register($path, HttpMethod::PUT, $handler);
    }

    public static function patch(string $path, HandlerContract $handler): void
    {
        self::register($path, HttpMethod::PATCH, $handler);
    }

    public static function delete(string $path, HandlerContract $handler): void
    {
        self::register($path, HttpMethod::DELETE, $handler);
    }

    private static function register(string $path, HttpMethod $httpMethod, HandlerContract $handler): void
    {
        self::$routes[] = new Route($path, $httpMethod, $handler);
    }

    public function emit(Response $response): void
    {
        http_response_code($response->status);
        foreach ($response->headers as $k => $v) {
            header("$k: $v");
        }
        echo $response->body;
    }
}
