<?php

declare(strict_types=1);

namespace App\Infra;

use App\Enums\Http;

class Router
{
    /** @var array <int, Route> */
    private static array $routes = [];

    private static Router $instance;

    private function __construct() {}

    public static function getInstance(): Router
    {
        if (! isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function handleRequest(string $path): void
    {
        /** @var string $currenMethod */
        $currenMethod = $_SERVER['REQUEST_METHOD'];
        $method = Http::from(strtolower($currenMethod));

        $callback = null;

        /** @var Route $route */
        foreach (self::$routes as $route) {
            if ($route->matchesPathAndMethod($path, $method)) {
                $callback = $route->getCallback();
            }
        }

        /** @todo handle not found routes */
        $callback = $callback ?? fn () => print 'not found';

        $data = Request::init();

        call_user_func($callback, $data);
    }

    private static function register(string $path, Http $method, callable $callback): void
    {
        self::$routes[] = new Route($path, $method, $callback);
    }

    public static function get(string $path, callable $callback): void
    {
        self::register($path, Http::GET, $callback);
    }

    public static function post(string $path, callable $callback): void
    {
        self::register($path, Http::POST, $callback);
    }

    public static function put(string $path, callable $callback): void
    {
        self::register($path, Http::PUT, $callback);
    }

    public static function patch(string $path, callable $callback): void
    {
        self::register($path, Http::PATCH, $callback);
    }

    public static function delete(string $path, callable $callback): void
    {
        self::register($path, Http::DELETE, $callback);
    }
}
