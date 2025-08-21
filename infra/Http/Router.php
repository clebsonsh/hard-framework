<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Exceptions\NotFoundException;
use Infra\Interfaces\RequestHandlerInterface;

class Router
{
    /** @var Route[] */
    private array $routes = [];

    /**
     * @todo handle not authorized requests
     * @todo handle method not allowed request
     */
    public function __construct(
        private readonly Request $request,
    ) {}

    public function get(string $path, RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::GET, $handler);
    }

    public function post(string $path, RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::POST, $handler);
    }

    public function put(string $path, RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::PUT, $handler);
    }

    public function patch(string $path, RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::PATCH, $handler);
    }

    public function delete(string $path, RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::DELETE, $handler);
    }

    private function register(string $path, HttpMethod $httpMethod, RequestHandlerInterface $handler): void
    {
        $this->routes[] = new Route($path, $httpMethod, $handler);
    }

    public function handleRequest(): Response
    {
        try {
            $response = $this->getRoute()->handle($this->request);
        } catch (NotFoundException) {
            $response = (new NotFoundHandler)->handle($this->request);
        }

        return $response;
    }

    /**
     * @throws NotFoundException
     */
    private function getRoute(): RequestHandlerInterface
    {
        foreach ($this->routes as $route) {
            if ($route->match($this->request)) {
                return $route->getHandler();
            }
        }

        throw new NotFoundException;
    }
}
