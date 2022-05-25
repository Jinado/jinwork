<?php

namespace Jinwork\Routing;

use Closure;
use Jinwork\Routing\Request\Request;
use Jinwork\Routing\Request\RequestMethod;

class Route
{
    /**
     * @var string
     */
    protected string $url;

    /**
     * @var RequestMethod[]
     */
    protected array $requestMethods;

    /**
     * @var array|Closure
     */
    protected array|Closure $callback;

    /**
     * @param string $url
     * @param RequestMethod[] $requestMethods
     * @param callable $callback
     */
    public function __construct(string $url, array $requestMethods, callable $callback)
    {
        // TODO: Validate the url

        $this->url = $url;
        $this->requestMethods = $requestMethods;
        $this->callback = $callback;
    }

    /**
     * Returns the URL for this route
     *
     * @return string
     * @since 1.1.0-alpha
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Returns the request method for this route
     *
     * @return RequestMethod[]
     * @since 1.1.0-alpha
     */
    public function getRequestMethods(): array
    {
        return $this->requestMethods;
    }

    /**
     * Sets this route's URL
     *
     * @param string $url
     * @return Route
     * @since 1.1.0-alpha
     */
    public function setUrl(string $url): Route
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Sets this route's request method
     *
     * @param RequestMethod[] $requestMethods
     * @return Route
     * @since 1.1.0-alpha
     */
    public function setRequestMethod(array $requestMethods): Route
    {
        $this->requestMethods = $requestMethods;
        return $this;
    }

    /**
     * Calls the callback with the appropriate arguments set
     *
     * @return void
     * @since 1.1.0-alpha
     */
    public function call(Request $request)
    {
        call_user_func($this->callback, $request, new Response());
    }
}