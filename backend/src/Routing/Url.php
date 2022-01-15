<?php

namespace Jinwork\Routing;

/**
 * @since 1.0.0-alpha
 */
class Url
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private string $authority;

    /**
     * @var string
     */
    private string $host;

    /**
     * @var string
     */
    private string $port;

    /**
     * @var string
     */
    private string $raw_query;

    /**
     * @var array
     */
    private array $parsed_query;

    /**
     * @var string
     */
    private string $fragment;

    /**
     * @var string
     */
    private string $scheme;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $password;

    /**
     * @since 1.0.0-alpha
     */
    public function __construct(string $url)
    {
        $this->parseUrl($url);
    }

    /**
     * @return string
     * @since 1.0.0-alpha
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Parses the URL and breaks it up into parts
     *
     * @param string $url
     * @return void
     * @since 1.0.0-alpha
     */
    private function parseUrl(string $url)
    {
        $matches = [];
        $this->url = $url;

        preg_match('@^(\w+)://@', $url, $matches);
        $this->scheme = $matches[1] ?? null;

        preg_match('@^\w+://(\w+:\w+\@)?([\w.-]+)@', $url, $matches);
        $this->host = $schemeMatch[1] ?? null;

        preg_match('@^\w+://(\w+:\w+\@)?[\w.]+:(\d+)@', $url, $matches);
        $this->port = $schemeMatch[2] ?? 80;

        preg_match('@^\w+://(\w+:\w+\@)?[\w.]+(:\d+|/)([/\w.-]+)(\?|#)@', $url, $matches);
        $this->path = $schemeMatch[3] ?? null;

        if($this->path) {
            $this->path = $this->path[0] === '/' ? $this->path : "/$this->path";
        }

        preg_match('@\?([\w=&\[\]-]+)@', $url, $matches);
        $this->raw_query = $schemeMatch[3] ?? null;

        $this->parseQuery();

        preg_match('@#([\w-]+)$@', $url, $matches);
        $this->fragment = $schemeMatch[1] ?? null;
    }

    /**
     * Parses the query if there is any
     *
     * @return void
     * @since 1.0.0-alpha
     */
    private function parseQuery()
    {
        if(!$this->raw_query) {
            $this->parsed_query = [];
            return;
        }
    }
}