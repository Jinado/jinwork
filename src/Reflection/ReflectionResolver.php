<?php

namespace Jinado\Jinwork\Reflection;

use ReflectionClass;
use ReflectionException;

/**
 * @since dev
 */
abstract class ReflectionResolver
{
    /**
     * @since dev
     */
    private function __construct() {}

    /**
     * Takes a class name and returns an instance of said class with all necessary dependencies injected
     *
     * @param string $className
     * @return void
     * @throws ReflectionException
     * @since dev
     */
    public static function getInstance(string $className) {
        $class = new ReflectionClass($className);

        var_dump($class->getName());
        die;
    }
}