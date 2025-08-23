<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Exceptions\NotFoundException;
use Infra\Http\Handlers\NotFoundHandler;
use Infra\Http\Handlers\RedirectHandler;
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

    public function redirect(string $from, string $to, int $status = 302): void
    {
        $this->get($from, new RedirectHandler($to, $status));
    }

    private function register(string $path, HttpMethod $httpMethod, RequestHandlerInterface $handler): void
    {
        $this->routes[] = new Route($path, $httpMethod, $handler);
    }

    public function handleRequest(): Response
    {
        try {
            $route = $this->getRoute();
            $this->request->setParams($route->getParams());
            $response = $route->getHandler()->handle($this->request);
        } catch (NotFoundException) {
            $response = (new NotFoundHandler)->handle($this->request);
        }

        return $response;
    }

    /**
     * @throws NotFoundException
     */
    private function getRoute(): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($this->request)) {
                return $route;
            }
        }

        throw new NotFoundException;
    }
}
