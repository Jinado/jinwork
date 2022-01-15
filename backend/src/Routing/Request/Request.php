<?php

namespace Jinwork\Routing\Request;

use Jinwork\Config;

/**
 * @since 1.0.0-alpha
 */
class Request
{
    /**
     * @var string // TODO: Change to URL class
     */
    private string $url;

    /**
     * @var string
     */
    private string $request_uri;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var string|null
     */
    private ?string $content_type;

    /**
     * @var RequestMethod|null
     */
    private ?RequestMethod $request_method;

    /**
     * @var array
     */
    private array $body;

    /**
     * @var array
     */
    private array $query_params;

    /**
     * @var Config
     */
    private Config $config;

    public function __construct(Config $config)
    {
        $this->initializeHeaders();
        $this->content_type = $this->getHeader('Content-Type');
        $this->request_method = RequestMethod::tryFrom(strtolower($_SERVER['REQUEST_METHOD']));
        $this->config = $config;

        $is_ssl = $this->config->getSafe('is_ssl');

        if($is_ssl) {
            $this->url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        } else {
            $this->url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }

        $this->request_uri = $_SERVER['REQUEST_URI'];

        $this->body = $_POST;
        $this->query_params = $_GET;
    }

    /**
     * @return string
     * @since 1.0.0-alpha
     */
    public function getRequestUri(): string
    {
        return $this->request_uri;
    }

    /**
     * @return string
     * @since 1.0.0-alpha
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     * @since 1.0.0-alpha
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Gets the value for the specified header
     *
     * @param $key
     * @return string|null
     * @since 1.0.0-alpha
     */
    public function getHeader($key): string|null
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * @return RequestMethod
     * @since 1.0.0-alpha
     */
    public function getRequestMethod(): RequestMethod
    {
        return $this->request_method;
    }

    /**
     * @return string
     * @since 1.0.0-alpha
     */
    public function getContentType(): string
    {
        return $this->content_type;
    }

    /**
     * Returns the request body
     *
     * @return array
     * @since 1.0.0-alpha
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Returns the query parameters
     *
     * @return array
     * @since 1.0.0-alpha
     */
    public function getQueryParameters(): array
    {
        return $this->query_params;
    }

    /**
     * Returns the parameters based on the request method
     *
     * @return array
     * @since 1.0.0-alpha
     */
    public function getParameters(): array
    {
        // TODO: Define the other request methods

        return match ($this->request_method) {
            RequestMethod::POST => $this->body,
            RequestMethod::GET => $this->query_params,
            default => [],
        };
    }

    /**
     * Returns a specific body parameter, or <b>NULL</b> if the parameter doesn't exist
     *
     * @param string $key
     * @return string|int|float|array|null
     * @since 1.0.0-alpha
     */
    public function getBodyParam(string $key): string|int|float|array|null
    {
        return $this->body[$key] ?? null;
    }

    /**
     * Retuns a specific query parameter, or <b>NULL</b> if the parameter doesn't exist
     *
     * @param string $key
     * @return string|int|float|array|null
     * @since 1.0.0-alpha
     */
    public function getQueryParameter(string $key): string|int|float|array|null
    {
        return $this->query_params[$key] ?? null;
    }

    /**
     * Returns the specific parameter based on the request method. Returns <b>NULL</b>
     * if the parameter doesn't exist
     *
     * @param string $key
     * @return string|int|float|array|null
     * @since 1.0.0-alpha
     */
    public function getParameter(string $key): string|int|float|array|null
    {
        return match ($this->request_method) {
            RequestMethod::POST => $this->body[$key] ?? null,
            RequestMethod::GET => $this->query_params[$key] ?? null,
            default => []
        };
    }

    /**
     * Initializes the header array
     *
     * @return void
     * @since 1.0.0-alpha
     */
    private function initializeHeaders()
    {
        $headers = [];

        foreach($_SERVER as $key => $value) {
            if(str_starts_with($key, "HTTP_")) {
                $header_name = ucwords(str_replace('_', '-', strtolower(substr($key, 5))), '-');
                $headers[$header_name] = $value;
            }
        }

        $this->headers = $headers;
    }
}