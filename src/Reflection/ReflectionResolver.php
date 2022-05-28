<?php

namespace Jinado\Jinwork\Reflection;

use Jinado\Jinwork\Controller\Controller;
use Jinado\Jinwork\Exception\InvalidRouteException;
use Jinado\Jinwork\Routing\Request\RequestMethod;
use Jinado\Jinwork\Routing\Route;
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
     * @param mixed ...$args
     * @return Controller
     * @throws InvalidRouteException
     * @throws ReflectionException
     * @since dev
     */
    public static function createNewInstance(string $className, ...$args): Controller
    {
        if(in_array(Controller::class, class_parents($className))) {
            return self::getControllerInstance($className, $args);
        }

        return new $className();
    }

    /**
     * @throws ReflectionException
     * @throws InvalidRouteException
     */
    private static function getControllerInstance(string $className, array $args = []): Controller
    {
        $reflectionClass = new ReflectionClass($className);
        $methods = $reflectionClass->getMethods();

        $methodNameRegex = self::getControllerRouteMethodNameRegex();
        $initialRouteRegex = self::getInitialControllerRouteRegex();
        $routeRegex = self::getControllerRouteRegex();

        /** @var Controller $instance */
        $instance = $reflectionClass->newInstanceArgs($args);

        foreach($methods as $method) {
            if(!preg_match($methodNameRegex, $method->getName())) continue;

            $fullMethodName = $reflectionClass->getName() . '::' . $method->getName();

            // Find the route object
            $matched = preg_match($initialRouteRegex, $method->getDocComment(), $routeComment);
            if(!$matched) continue;

            $routeComment = $routeComment[0];
            $matched = preg_match($routeRegex, $routeComment, $routeData);
            if(!$matched) throw new InvalidRouteException($fullMethodName . ' has an invalid route signature');

            $uri = $routeData['uri'] ?? null;
            $requestMethods = $routeData['requestMethods'] ?? null;

            if(!$uri) throw new InvalidRouteException($fullMethodName . ' has an invalid URI formatting');
            if(!$requestMethods) throw new InvalidRouteException($fullMethodName . ' has invalid request method formatting');

            $len = strlen('RequestMethod::');
            $requestMethods = array_map(function ($el) use ($len, $fullMethodName) {
                $method = substr(trim($el), $len);
                if(!$method) throw new InvalidRouteException($fullMethodName . ' has an invalid method. Unable to parse it.');

                $requestMethod = RequestMethod::tryFrom(strtolower($method));
                if(!$requestMethod) throw new InvalidRouteException($fullMethodName . ' has an invalid method: ' . $method);

                return $requestMethod;
            }, explode(',', $requestMethods));

            $uri = trim($uri);
            $route = new Route($uri, $requestMethods, [$instance, $method->getName()]);
            $instance->getRouter()->registerRoute($route);
        }

        return $instance;
    }

    private static function getControllerRouteMethodNameRegex(): string
    {
        return '/^\w+Route$/';
    }

    private static function getInitialControllerRouteRegex(): string
    {
        return '/@Jinado\\\Jinwork\\\Routing\\\Route.+/';
    }

    private static function getControllerRouteRegex(): string
    {
        return <<<REGEXP
/Route\(\s*(?>"|')(?'uri'.+)(?>"|'),\s*\[(?'requestMethods'(?>\s*RequestMethod::\w+,*\s*)+)\]\s*\)/
REGEXP;

    }
}