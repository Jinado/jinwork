<?php

namespace Jinwork;

/**
 * @since 1.0.0-alpha
 */
class Application
{
    private const JINWORK_VERSION = '1.0.0-alpha';

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @since 1.0.0-alpha
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initializes the entire framework
     *
     * @since 1.0.0-alpha
     */
    private function initialize()
    {
        $this->config = new Config();
    }

    /**
     * @since 1.0.0-alpha
     * @return string
     */
    public function getVersion(): string
    {
        return self::JINWORK_VERSION;
    }
}