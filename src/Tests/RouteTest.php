<?php

namespace Jinado\Jinwork\Tests;

use Jinado\Jinwork\Routing\Request\Request;
use Jinado\Jinwork\Routing\Request\RequestMethod;
use Jinado\Jinwork\Routing\Response\Response;
use Jinado\Jinwork\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testRoute(): void
    {
        $sideEffect = 'before change';
        $this->assertEquals('before change', $sideEffect);

        $route = new Route('/', [ RequestMethod::GET ], function (Request $request, Response $response) use (&$sideEffect) {
            $sideEffect = 'after change';
        });

        $requestData = [
            'REQUEST_METHOD' => 'GET',
            'HTTPS' => 'on',
            'HTTP_HOST' => 'www.example.com',
            'REQUEST_URI' => '/uri-part-1',
            'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded',
            'HTTP_X_BROWSER' => 'none'
        ];

        $request = new Request($requestData);
        $route->call($request);
        $this->assertEquals('after change', $sideEffect, 'The side effect variable was not changed by the route being accessed');

        $this->assertEquals('https://www.example.com/uri-part-1', $request->getUrl()->getUrl(), 'The URL for the request was incorrect');
        $this->assertEquals('none', $request->getHeader('X-Browser'), 'The header X-Browser was not set properly');
        $this->assertEquals([
            'Host' => 'www.example.com',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-Browser' => 'none'
        ], $request->getHeaders(), 'The header array was not properly set');

        $this->assertEquals([ RequestMethod::GET ], $route->getRequestMethods(), 'The route\'s request methods do not match what they should be');
    }
}