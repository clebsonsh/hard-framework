<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Exceptions\NotFoundException;
use Infra\Interfaces\RequestHandlerInterface;
use RuntimeException;

class Router
{
    /** @var array <int, Route> */
    private static array $routes = [];

    /** @todo handle not authorized requests */
    /** @todo handle method not allowed request */
    public static function handleRequest(string $path): Response
    {
        $httpMethod = self::detectHttpMethod();

        try {
            return self::getRoute($path, $httpMethod)->handle(new Request);
        } catch (NotFoundException $notFoundException) {
            return (new NotFoundHandler)->handle(new Request($notFoundException->getMessage()));
        }
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
     * @throws NotFoundException
     */
    private static function getRoute(string $path, HttpMethod $httpMethod): RequestHandlerInterface
    {
        /** @var Route $route */
        foreach (self::$routes as $route) {
            if ($route->match($path, $httpMethod)) {
                return $route->getHandler();
            }
        }

        throw new NotFoundException;
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
}
