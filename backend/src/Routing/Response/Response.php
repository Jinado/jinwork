<?php

namespace Jinwork\Routing;

use JetBrains\PhpStorm\NoReturn;

/**
 * @since 1.1.0-alpha
 */
class Response
{
    #[NoReturn] public function send($content)
    {
        // TODO: Add headers
        // TODO: Add status

        die($content);
    }
}