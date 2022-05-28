<?php

namespace Jinado\Jinwork\Controller;

use Jinado\Jinwork\Routing\Router;

abstract class Controller
{
    protected Router $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function getRouter(): Router{
        return $this->router;
    }
}