<?php

namespace Jinwork\Routing;

class Router
{
    /**
     * @var Route[]
     */
    private array $routes;

    public function registerRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}