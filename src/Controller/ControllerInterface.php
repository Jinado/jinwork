<?php

namespace Jinado\Jinwork\Controller;

use Jinado\Jinwork\Routing\Router;

interface ControllerInterface
{
    public function __construct(Router $router);
}