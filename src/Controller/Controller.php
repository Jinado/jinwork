<?php

namespace Jinado\Jinwork\Controller;

use Jinado\Jinwork\Routing\Router;

abstract class Controller implements ControllerInterface
{
    protected Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
        $this->instantiateRoutes();
    }
}