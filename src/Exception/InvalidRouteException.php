<?php

namespace Jinado\Jinwork\Exception;

use Exception;
use Throwable;

/**
 * @since dev
 */
class InvalidRouteException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     *
     * @since 1.1.0-alpha
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if(!$message) {
            $message = 'Invalid URL';
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @since 1.1.0-alpha
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}