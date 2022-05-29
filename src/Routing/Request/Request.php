<?php

namespace Jinado\Jinwork\Routing\Request;

use Jinado\Jinwork\Exception\InvalidUrlException;
use Jinado\Jinwork\Routing\Url\UrlImmutable;
use SimpleXMLElement;
use stdClass;

/**
 * @since 1.0.0-alpha
 */
class Request
{
    /**
     * @var \Jinado\Jinwork\Routing\Url\UrlImmutable
     */
    private UrlImmutable $url;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var RequestMethod|null
     */
    private ?RequestMethod $request_method;

    /**
     * @var array|null
     */
    private ?array $body;

    /**
     * @var string|null
     */
    private ?string $raw_body;

    /**
     * Constructs a request object for the current request
     *
     * @param null|array $requestData
     * @throws InvalidUrlException
     * @since 2.2.0-alpha
     */
    public function __construct(?array $requestData = null)
    {
        if(!$requestData) $requestData = $_SERVER;

        $this->initializeHeaders($requestData);
        $this->request_method = RequestMethod::tryFrom(strtolower($requestData['REQUEST_METHOD']));

        $url = (isset($requestData['HTTPS']) && $requestData['HTTPS'] === 'on' ? "https" : "http") . "://$requestData[HTTP_HOST]$requestData[REQUEST_URI]";

        $this->url = new UrlImmutable($url);

        if("application/x-www-form-urlencoded" === $this->getHeader('Content-Type')) {
            $this->body = $_POST;
        } else {
            $this->raw_body = file_get_contents('php://input') ?: NULL;
        }
    }

    /**
     * @return UrlImmutable
     * @since 1.0.0-alpha
     */
    public function getUrl(): UrlImmutable
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
     * @param string $key
     * @return string|null
     * @since 1.1.0-alpha
     */
    public function getHeader(string $key): string|null
    {
        $key = ucwords($key, '-');
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
     * Returns the request body. Only contains data sent as <b>application/x-www-form-urlencoded</b>
     *
     * @return array
     * @since 1.0.0-alpha
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Returns the request body. Contains data sent as something other than <b>application/x-www-form-urlencoded<b>
     *
     * @return string|null
     * @since 1.1.0-alpha
     */
    public function getRawBody(): ?string
    {
        return $this->raw_body;
    }

    /**
     * Returns the body as JSON
     *
     * @return stdClass
     * @since 1.1.0-alpha
     */
    public function getBodyAsJSON(): stdClass
    {
        if("application/json" !== $this->getHeader('Content-Type')) return new stdClass();

        return json_decode($this->raw_body);
    }

    /**
     * Returns the body as XML
     *
     * @return SimpleXMLElement|null
     * @since 1.1.0-alpha
     */
    public function getBodyAsXML(): ?SimpleXMLElement
    {
        if("application/xml" !== $this->getHeader('Content-Type')) return NULL;

        return simplexml_load_string($this->raw_body) ?: NULL;
    }

    /**
     * Returns the parameters based on the request method.<br>
     * Currently supports only <b>POST</b> and <b>GET</b> requests
     *
     * @return string|array
     * @since 1.0.0-alpha
     */
    public function getParameters(): string|array
    {
        // TODO: Define the other request methods

        switch($this->request_method) {
            case RequestMethod::GET:
                return $this->url->getParsedQuery();
            case RequestMethod::POST:
                if("application/x-www-form-urlencoded" === $this->getHeader('Content-Type')) {
                    return $this->body;
                }

                return $this->raw_body;
            default:
                return [];
        }
    }

    /**
     * Returns a specific body parameter, or <b>NULL</b> if the parameter doesn't exist.
     * Supports dot notation for nested values
     *
     * @param string $key
     * @return string|int|float|array|null
     * @since 1.1.0-alpha
     */
    public function getBodyParam(string $key): string|int|float|array|null
    {
        $keys = explode('.', $key);

        if(count($keys) === 1) return $this->body[$key] ?? NULL;

        $value = [];
        foreach ($keys as $_key) {
            $value = $this->body[$_key] ?? NULL;
            if(is_string($value) || NULL === $value) return $value;
        }

        return $value ?? NULL;
    }

    /**
     * Returns a specific query parameter, or <b>NULL</b> if the parameter doesn't exist.
     * Supports dot notation for nested values
     *
     * @param string $key
     * @return string|int|float|array|null
     * @since 1.1.0-alpha
     */
    public function getQueryParameter(string $key): string|int|float|array|null
    {
        $query_params = $this->url->getParsedQuery();
        $keys = explode('.', $key);

        if(count($keys) === 1) return $query_params[$key] ?? NULL;

        $value = [];
        foreach ($keys as $_key) {
            $value = $query_params[$_key] ?? NULL;
            if(is_string($value) || NULL === $value) return $value;
        }

        return $value ?? NULL;
    }

    /**
     * Returns the specific parameter based on the request method. Returns <b>NULL</b>
     * if the parameter doesn't exist. Supports dot notation for nested values.
     *
     * @param string $key
     * @return string|int|float|array|null
     * @since 1.1.0-alpha
     */
    public function getParameter(string $key): string|int|float|array|null
    {
        return match ($this->request_method) {
            RequestMethod::POST => $this->getBodyParam($key),
            RequestMethod::GET => $this->getQueryParameter($key),
            default => null
        };
    }

    /**
     * Initializes the header array
     *
     * @param null|array $requestData
     * @return void
     * @since 2.2.0-alpha
     */
    private function initializeHeaders(?array $requestData = null)
    {
        if(!$requestData) $requestData = $_SERVER;

        $headers = [];

        foreach($requestData as $key => $value) {
            if(str_starts_with($key, "HTTP_")) {
                $header_name = ucwords(str_replace('_', '-', strtolower(substr($key, 5))), '-');
                $headers[$header_name] = $value;
            }
        }

        $this->headers = $headers;
    }
}