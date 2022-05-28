<?php

namespace Jinado\Jinwork\Controller;

use Jinado\Jinwork\Routing\Router;

interface ControllerInterface
{
    function __construct(Router $router);
    function instantiateRoutes();
}