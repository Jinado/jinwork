<?php

namespace Jinado\Jinwork\Controller;

use Jinado\Jinwork\Routing\Router;

/**
 * @since 2.0.0-alpha
 */
abstract class Controller
{
    protected Router $router;

    /**
     * The constructor of the default controller class. This constructor should only be called internally by Jinwork.
     *
     * @param Router $router
     * @since 2.0.0-alpha
     */
    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * @return Router
     * @since 2.0.0-alpha
     */
    public function getRouter(): Router {
        return $this->router;
    }
}