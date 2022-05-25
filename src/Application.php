<?php

namespace Jinwork;

use Jinwork\Routing\Request\Request;
use Jinwork\Routing\Response;
use Jinwork\Routing\Router;

require_once '../vendor/autoload.php';

/**
 * @since 1.0.0-alpha
 */
class Application
{
    private const JINWORK_VERSION = '1.1.0-alpha';

    protected ?Router $router;

    public function __construct(?Router $router = null)
    {
        if($router) $this->router = $router;
    }

    /**
     * Registers the class to use a Router
     *
     * @param Router $router
     * @since 1.1.0-alpha
     */
    public function registerRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Runs the application. Basically listens for any connection to any of the specific routes
     *
     * @return void
     * @since 1.1.0-alpha
     */
    public function run()
    {
        if(!$this->router) {
            // TODO: Throw exception
        }

        $routes = $this->router->getRoutes();

        $request = new Request();
        foreach($routes as $route) {
            // TODO: Make sure the request method matches
            // TODO: Handle cool URLs like /some-user/:id and whatnot
            if($route->getUrl() === $request->getUrl()->getPath()) {
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