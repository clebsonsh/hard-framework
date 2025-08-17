<?php

declare(strict_types=1);

namespace App\Infra;

use App\Enums\Http;

class Route
{
    /** @var <Http, <string, callable>> */
    private static array $routes = [];

    private static $instance;

    private function __construct() {}

    public static function getInstance(): static
    {
        if (is_null(self::$instance)) {
            return new static;
        }

        return self::$instance;
    }

    public function handleRequest(string $path): void
    {
        $method = Http::from(strtolower($_SERVER['REQUEST_METHOD']));

        $callback = null;

        foreach (self::$routes as $route) {
            if ($route['path'] === $path and $route['method'] === $method) {
                $callback = $route['callback'];
            }
        }

        $callback = $callback ?? fn () => print 'not found';

        $data = Request::init();

        call_user_func($callback, $data);
    }

    private static function register(string $path, Http $method, callable $callback)
    {
        self::$routes[] = [
            'path' => $path,
            'method' => $method,
            'callback' => $callback,
        ];
    }

    public static function get(string $path, callable $callback)
    {
        self::register($path, Http::GET, $callback);
    }

    public static function post(string $path, callable $callback)
    {
        self::register($path, Http::POST, $callback);
    }

    public static function put(string $path, callable $callback)
    {
        self::register($path, Http::PUT, $callback);
    }

    public static function pacth(string $path, callable $callback)
    {
        self::register($path, Http::PATCH, $callback);
    }

    public static function deletE(string $path, callable $callback)
    {
        self::register($path, Http::DELETE, $callback);
    }
}
