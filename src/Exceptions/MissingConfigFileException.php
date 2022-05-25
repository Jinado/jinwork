<?php

namespace Jinwork\Exception;

use Exception;
use Throwable;

/**
 * @since 1.0.0-alpha
 */
class MissingConfigFileException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     *
     * @since 1.0.0-alpha
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if(!$message) {
            $message = 'Missing config file. The config file needs to exist can be empty, but it has to exist';
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @since 1.0.0-alpha
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }
}