<?php

namespace Jinado\Jinwork\Routing;

use Closure;
use JetBrains\PhpStorm\NoReturn;
use Jinado\Jinwork\Exception\InvalidUrlException;
use Jinado\Jinwork\Routing\Request\Request;
use Jinado\Jinwork\Routing\Request\RequestMethod;
use Jinado\Jinwork\Routing\Response\Response;
use Jinado\Jinwork\Routing\Url\UrlImmutable;

class Route
{
    /**
     * @var UrlImmutable
     */
    protected UrlImmutable $url;

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
     * @throws InvalidUrlException
     */
    public function __construct(string $url, array $requestMethods, callable $callback)
    {
        $this->url = new UrlImmutable($url);
        $this->requestMethods = $requestMethods;
        $this->callback = $callback;
    }

    /**
     * Returns the URL for this route
     *
     * @return UrlImmutable
     * @since 1.1.0-alpha
     */
    public function getUrl(): UrlImmutable
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
     * @throws InvalidUrlException
     * @since 1.1.0-alpha
     */
    public function setUrl(string $url): Route
    {
        $this->url = new UrlImmutable($url);
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
     * @param Request $request
     * @return void
     * @since 2.2.0-alpha
     */
    #[NoReturn] public function call(Request $request): void
    {
        call_user_func($this->callback, $request, new Response());
    }
}