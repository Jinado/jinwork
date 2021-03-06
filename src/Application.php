<?php

namespace Jinado\Jinwork;

use JetBrains\PhpStorm\NoReturn;
use Jinado\Jinwork\Controller\Controller;
use Jinado\Jinwork\Exception\InvalidOrMissingConfigurationException;
use Jinado\Jinwork\Reflection\ReflectionResolver;
use Jinado\Jinwork\Routing\Request\Request;
use Jinado\Jinwork\Routing\Response\Response;
use Jinado\Jinwork\Routing\Router;
use ReflectionException;

/**
 * @since 1.0.0-alpha
 */
class Application
{
    private const JINWORK_VERSION = '2.0.0-alpha';

    protected ?Router $router;

    /**
     * @throws InvalidOrMissingConfigurationException|ReflectionException
     * @throws Exception\InvalidRouteException
     * @since 2.0.0-alpha
     */
    public function __construct()
    {
        if(!defined('PROJECT_SRC') || !is_dir(PROJECT_SRC) || !is_readable(PROJECT_SRC)) {
            throw new InvalidOrMissingConfigurationException();
        }

       $router = new Router();

        $controllerLocation =  PROJECT_SRC . '/Controller/';
        $files = scandir($controllerLocation);

        foreach($files as $file) {
            if(!preg_match('/\w+Controller\.php/', $file)) continue;
            $fileName = $controllerLocation . $file;
            $className = substr($file, 0, -4);
            include $fileName;
        }

        $declared_classes = get_declared_classes();
        foreach($declared_classes as $declared_class) {
            if(in_array(Controller::class, class_parents($declared_class))) {
                ReflectionResolver::createNewInstance($declared_class, $router);
            }
        }

        $this->router = $router;
    }

    /**
     * Runs the application. Basically listens for any connection to any of the specific routes
     *
     * @return void
     * @since 2.1.0-alpha
     */
    #[NoReturn] public function run(): void
    {
        if(!$this->router) {
            // TODO: Throw exception
        }

        $routes = $this->router->getRoutes();

        $request = new Request();
        foreach($routes as $route) {
            // TODO: Handle cool URLs like /some-user/:id and whatnot
            if($route->getUrl() === $request->getUrl()->getPath() && in_array($request->getRequestMethod(), $route->getRequestMethods())) {
                $route->call($request);
            }
        }

        (new Response())->send('<h1>404 - No Route Found</h1>');
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