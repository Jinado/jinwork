<?php

namespace Jinado\Jinwork\Routing;

use Jinado\Jinwork\Routing\Request\Request;

/**
 * @since 2.2.0-alpha
 */
class RouteMatcher
{
    /**
     * @var null|Route
     */
    private ?Route $route;

    /**
     * @var null|Request
     */
    private ?Request $request;

    /**
     * @param null|Route $route
     * @param null|Request $request
     * @since 2.2.0-alpha
     */
    public function __construct(?Route $route = null, ?Request $request = null)
    {
        $this->route = $route;
        $this->request = $request;
    }

    /**
     * @return Route|null
     * @since 2.2.0-alpha
     */
    public function getRoute(): ?Route
    {
        return $this->route;
    }

    /**
     * @param Route|null $route
     * @return RouteMatcher
     * @since 2.2.0-alpha
     */
    public function setRoute(?Route $route): RouteMatcher
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return Request|null
     * @since 2.2.0-alpha
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @param Request|null $request
     * @return RouteMatcher
     * @since 2.2.0-alpha
     */
    public function setRequest(?Request $request): RouteMatcher
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Checks whether a route matches the given request
     *
     * @since 2.2.0-alpha
     * @return bool
     */
    public function matchesRequest(): bool
    {
        if(!$this->route) return false;
        if(!$this->request) return false;

        if(!$this->matchesUri()) return false;
        if(!$this->matchesOneRequestMethod()) return false;
        return true;
    }

    /**
     * @return bool
     * @since 2.2.0-alpha
     */
    private function matchesUri(): bool
    {
        return $this->route->getUrl()->getPath() === $this->request->getUrl()->getPath();
    }

    /**
     * @return bool
     * @since 2.2.0-alpha
     */
    private function matchesOneRequestMethod(): bool
    {
        return in_array($this->request->getRequestMethod(), $this->route->getRequestMethods());
    }
}