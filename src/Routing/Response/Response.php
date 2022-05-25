<?php

namespace Jinwork\Routing;

use JetBrains\PhpStorm\NoReturn;

require_once __DIR__ . '/StatusCode.php';

/**
 * @since 1.1.0-alpha
 */
class Response
{
    /**
     * @var string
     */
    protected string $content;

    /**
     * @var array
     */
    protected array $headers;

    /**
     * @var StatusCode|int
     */
    protected StatusCode|int $statusCode;

    /**
     * @since 1.1.0-alpha
     */
    public function __construct()
    {
        $this->statusCode = StatusCode::OK;
    }

    #[NoReturn] public function send($content)
    {
        // TODO: Add headers
        // TODO: Add status

        die($content);
    }
}