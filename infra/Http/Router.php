<?php

declare(strict_types=1);

namespace Infra\Http;

use Infra\Enums\HttpMethod;
use Infra\Exceptions\MethodNotAllowedException;
use Infra\Exceptions\NotFoundException;
use Infra\Http\Handlers\MethodNotAllowedHandler;
use Infra\Http\Handlers\NotFoundHandler;
use Infra\Http\Handlers\RedirectHandler;
use Infra\Interfaces\RequestHandlerInterface;

class Router
{
    /** @var Route[] */
    private array $routes = [];

    private bool $pathMatched = false;

    /**
     * @todo handle not authorized requests
     */
    public function __construct(
        private readonly Request $request,
    ) {}

    /** @param  class-string<RequestHandlerInterface>|RequestHandlerInterface  $handler */
    public function get(string $path, string|RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::GET, $handler);
    }

    /** @param  class-string<RequestHandlerInterface>|RequestHandlerInterface  $handler */
    public function post(string $path, string|RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::POST, $handler);
    }

    /** @param  class-string<RequestHandlerInterface>|RequestHandlerInterface  $handler */
    public function put(string $path, string|RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::PUT, $handler);
    }

    /** @param  class-string<RequestHandlerInterface>|RequestHandlerInterface  $handler */
    public function patch(string $path, string|RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::PATCH, $handler);
    }

    /** @param  class-string<RequestHandlerInterface>|RequestHandlerInterface  $handler */
    public function delete(string $path, string|RequestHandlerInterface $handler): void
    {
        $this->register($path, HttpMethod::DELETE, $handler);
    }

    public function redirect(string $from, string $to, int $status = 302): void
    {
        $this->get($from, new RedirectHandler($to, $status));
    }

    /** @param  class-string<RequestHandlerInterface>|RequestHandlerInterface  $handler */
    private function register(string $path, HttpMethod $httpMethod, string|RequestHandlerInterface $handler): void
    {
        $this->routes[] = new Route($path, $httpMethod, $handler);
    }

    public function handleRequest(): Response
    {
        try {
            $route = $this->getRoute();

            $this->request->setParams($route->getParams());

            /** @var class-string<RequestHandlerInterface>|RequestHandlerInterface $handler */
            $handler = $route->getHandler();
            $response = is_string($handler)
                ? (new $handler)->handle($this->request)
                : $handler->handle($this->request);
        } catch (NotFoundException) {
            $response = (new NotFoundHandler)->handle($this->request);
        } catch (MethodNotAllowedException) {
            $response = (new MethodNotAllowedHandler)->handle($this->request);
        }

        return $response;
    }

    /**
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     */
    private function getRoute(): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($this->request)) {
                return $route;
            }

            if ($route->matchPath($this->request)) {
                $this->pathMatched = true;
            }
        }

        if ($this->pathMatched) {
            throw new MethodNotAllowedException;
        }

        throw new NotFoundException;
    }
}
