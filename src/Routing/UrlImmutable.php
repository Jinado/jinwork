<?php

namespace Jinado\Jinwork\Routing;

use Jinado\Jinwork\Exception\InvalidUrlException;

/**
 * @since 1.0.0-alpha
 */
class UrlImmutable
{
    /**
     * @var string
     */
    protected string $url;

    /**
     * @var ?string
     */
    protected ?string $path;

    /**
     * @var ?string
     */
    protected ?string $host;

    /**
     * @var int
     */
    protected int $port;

    /**
     * @var ?string
     */
    protected ?string $raw_query;

    /**
     * @var array
     */
    protected array $parsed_query;

    /**
     * @var ?string
     */
    protected ?string $fragment;

    /**
     * @var ?string
     */
    protected ?string $scheme;

    /**
     * @var ?string
     */
    protected ?string $username;

    /**
     * @var ?string
     */
    protected ?string $password;

    /**
     * @throws InvalidUrlException
     * @since 1.0.0-alpha
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->parseUrl();
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @return ?int
     * @since 1.0.0-alpha
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getRawQuery(): ?string
    {
        return $this->raw_query;
    }

    /**
     * @return array
     * @since 1.0.0-alpha
     */
    public function getParsedQuery(): array
    {
        return $this->parsed_query;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return ?string
     * @since 1.0.0-alpha
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Parses the url
     *
     * @return void
     * @throws InvalidUrlException
     * @since 1.0.0-alpha
     */
    protected function parseUrl(): void
    {
        $parsed_url = parse_url($this->url);
        if(FALSE === $parsed_url) {
            throw new InvalidUrlException();
        }

        $this->scheme = $parsed_url['scheme'] ?? null;
        $this->host = $parsed_url['host'] ?? null;
        $this->port = $parsed_url['port'] ?? 80;
        $this->username = $parsed_url['user'] ?? null;
        $this->password = $parsed_url['pass'] ?? null;
        $this->path = $parsed_url['path'] ?? '/';
        $this->fragment = $parsed_url['fragment'] ?? null;
        $this->raw_query = $parsed_url['query'] ?? null;
        $this->parsed_query = [];

        if($this->raw_query) {
            parse_str($this->raw_query, $this->parsed_query);
        }
    }
}