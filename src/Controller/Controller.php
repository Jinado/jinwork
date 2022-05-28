<?php

namespace Jinado\Jinwork\Controller;

use Jinado\Jinwork\Routing\Router;

class Controller implements ControllerInterface
{
    protected Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
        $this->instantiateRoutes();
    }

    function instantiateRoutes() {}
}