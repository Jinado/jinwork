<?php

namespace Jinwork\Routing;

class Url extends UrlImmutable
{
    public static function createFromImmutableUrl(UrlImmutable $url_immutable): Url
    {
        return new Url($url_immutable->getUrl());
    }

    /**
     * Sets the host for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setUrl(string $value): Url
    {
        $this->url = $value;
        $this->parseUrl();
        return $this;
    }

    /**
     * Sets the path for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setPath(string $value): Url
    {
        $this->path = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the host for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setHost(string $value): Url
    {
        $this->host = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the port for the url and updates the current URL
     *
     * @param int $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setPort(int $value): Url
    {
        $this->port = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the raw query for the url and updates the current URL as well as the parsed query
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setRawQuery(string $value): Url
    {
        $this->raw_query = $value;
        $this->updateParsedQuery();
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the parsed query for the url and updates the current URL as well as the raw query
     *
     * @param array $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setParsedQuery(array $value): Url
    {
        $this->parsed_query = $value;
        $this->updateRawQuery();
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the scheme for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setScheme(string $value): Url
    {
        $this->scheme = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the fragment for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setFragment(string $value): Url
    {
        $this->fragment = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the username for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setUsername(string $value): Url
    {
        $this->username = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Sets the password for the url and updates the current URL
     *
     * @param string $value
     * @return Url
     * @since 1.0.0-alpha
     */
    public function setPassword(string $value): Url
    {
        $this->password = $value;
        $this->updateUrl();
        return $this;
    }

    /**
     * Updates the parsed query so that it is synced with the raw query
     *
     * @return void
     * @since 1.0.0-alpha
     */
    private function updateParsedQuery()
    {
        parse_str($this->raw_query, $this->parsed_query);
    }

    /**
     * Updates the raw query so that it is synced with the parsed query
     *
     * @return void
     * @since 1.0.0-alpha
     */
    private function updateRawQuery()
    {
        
    }

    /**
     * Updates the URL based on the values of all the different parts of the URL
     *
     * @return void
     * @since 1.0.0-alpha
     */
    private function updateUrl()
    {
        $scheme   = $this->scheme ? $this->scheme . '://' : '';
        $host     = $this->host ?? '';
        $port     = $this->port ? ':' . $this->port : '';
        $user     = $this->user ?? '';
        $pass     = $this->password ? ':' . $this->password  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = $this->path ?? '';
        $query    = $this->raw_query ? '?' . $this->raw_query : '';
        $fragment = $this->fragment ? '#' . $this->fragment : '';

        if(":80" === $port) $port = '';

        $this->url = "$scheme$user$pass$host$port$path$query$fragment";
    }
}