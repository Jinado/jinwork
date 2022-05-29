<?php

namespace Jinado\Jinwork\Tests;

use Jinado\Jinwork\Routing\Request\Request;
use Jinado\Jinwork\Routing\Request\RequestMethod;
use Jinado\Jinwork\Routing\Response\Response;
use Jinado\Jinwork\Routing\Route;
use Jinado\Jinwork\Routing\RouteMatcher;
use PHPUnit\Framework\TestCase;

class RouteMatcherTest extends TestCase
{
    /**
     * @var RouteMatcher
     */
    private RouteMatcher $routeMatcher;

    public function setUp(): void
    {
        $this->routeMatcher = new RouteMatcher();
    }

    public function tearDown(): void
    {
        $this->routeMatcher->setRequest(null)->setRoute(null);
    }

    public function testThatRouteMatchersPropertiesAreNullInitially(): void
    {
        $this->assertEquals(null, $this->routeMatcher->getRoute(), 'The initial route was not NULL');
        $this->assertEquals(null, $this->routeMatcher->getRequest(), 'The initial request was not NULL');
    }

    public function testThatRouteMatcherMatchesCorrectly(): void
    {
        $routes = [
            new Route('/login', [ RequestMethod::GET ], function (Request $request, Response $response) use (&$loginGetSideEffect) {
                $loginGetSideEffect = 'Login GET';
            }),

            new Route('/my-account', [ RequestMethod::GET ], function (Request $request, Response $response) use (&$myAccountGetSideEffect) {
                $myAccountGetSideEffect = 'My Account GET';
            }),

            new Route('/login', [ RequestMethod::POST ], function (Request $request, Response $response) use (&$loginPostSideEffect) {
                $loginPostSideEffect = 'Login POST';
            }),
        ];

        $request = [
            'REQUEST_METHOD' => 'GET',
            'HTTPS' => 'on',
            'HTTP_HOST' => 'www.example.com',
            'REQUEST_URI' => '/login',
            'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded',
            'HTTP_X_BROWSER' => 'none'
        ];

        $this->setupNewRouteAndRequestTest($request, $routes, 'Login GET', $loginGetSideEffect);

        $request['REQUEST_METHOD'] = 'POST';
        $this->setupNewRouteAndRequestTest($request, $routes, 'Login POST', $loginPostSideEffect);

        $request['REQUEST_METHOD'] = 'GET';
        $request['REQUEST_URI'] = '/my-account';
        $this->setupNewRouteAndRequestTest($request, $routes, 'My Account GET', $myAccountGetSideEffect);
    }

    private function setupNewRouteAndRequestTest(array $requestData, array $routes, string $expected, &$sideEffect): void
    {
        $request = new Request($requestData);

        $this->routeMatcher->setRequest($request);
        $this->assertEquals($request, $this->routeMatcher->getRequest(), 'The request was not properly set');

        /** @var Route $route */
        foreach($routes as $route) {
            $this->routeMatcher->setRoute($route);
            if($this->routeMatcher->matchesRequest()) {
                $route->call($this->routeMatcher->getRequest());
            }
        }

        $this->assertEquals($expected, $sideEffect, sprintf('The %s-route was not properly accessed', $expected));
    }
}