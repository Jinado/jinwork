<?php

namespace Jinwork\Controller;

use Jinwork\Routing\Router;

interface ControllerInterface
{
    public function __construct(Router $router);
}