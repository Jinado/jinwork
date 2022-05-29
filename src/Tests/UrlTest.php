<?php

namespace Jinado\Jinwork\Tests;

use Jinado\Jinwork\Routing\Url\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testUrlParseFunction(): void
    {
        $url = new Url('https://www.example.com');
        $this->assertEquals('https', $url->getScheme(), 'UrlImmutable::getScheme() did not return the protocol https for https://www.example.com');
        $this->assertEquals(null, $url->getUsername(), 'UrlImmutable::getPassword() did not return NULL for https://www.example.com');
        $this->assertEquals(null, $url->getPassword(), 'UrlImmutable::getPassword() did not return NULL for https://www.example.com');
        $this->assertEquals('www.example.com', $url->getHost(), 'UrlImmutable::getHost() did not return the host www.example.com for https://www.example.com');
        $this->assertEquals('/', $url->getPath(), 'UrlImmutable::getPath() did not return the path / for https://www.example.com');
        $this->assertEquals(null, $url->getFragment(), 'UrlImmutable::getFragment() did not return the fragment NULL for https://www.example.com');
        $this->assertEquals([], $url->getParsedQuery(), 'UrlImmutable::getParsedQuery() did not return an empty array for https://www.example.com');
        $this->assertEquals(null, $url->getRawQuery(), 'UrlImmutable::getRawQuery() did not return NULL for https://www.example.com');
        $this->assertEquals(80, $url->getPort(), 'UrlImmutable::getPort() did not return the integer 80 for https://www.example.com');

        $url->setUrl('https://www.example.com/uri-part-1');
        $this->assertEquals('https://www.example.com/uri-part-1', $url->getUrl(), 'The URL does not seem to have changed');
        $this->assertEquals('/uri-part-1', $url->getPath(), 'The path was not equal to /uri-part-1');

        $url->setPort(8080);
        $this->assertEquals(8080, $url->getPort(), 'The port was not changed to 8080');
        $this->assertEquals('https://www.example.com:8080/uri-part-1', $url->getUrl(), 'The URL was not updated after changing the port to port 8080');

        $url->setUrl('https://www.example.com/uri-part-1?foo=bar&baz[]=baq&baz[]=moo');
        $this->assertEquals('https://www.example.com/uri-part-1?foo=bar&baz[]=baq&baz[]=moo', $url->getUrl(), 'The URL did not get properly updated with the new query args');
        $this->assertEquals('foo=bar&baz[]=baq&baz[]=moo', $url->getRawQuery(), 'The raw query was not properly formatted');
        $this->assertEquals([
            'foo' => 'bar',
            'baz' => [ 'baq', 'moo' ]
        ], $url->getParsedQuery(), 'The parsed query was not parsed correctly');

        $url->setUsername('user');
        $url->setPassword('pass');
        $this->assertEquals('https://user:pass@www.example.com/uri-part-1?foo=bar&baz[]=baq&baz[]=moo', $url->getUrl(), 'The user and pass was not properly injected into the URL');

        $url->setRawQuery('');
        $url->setHost('example.com');
        $url->setFragment('some-cool-fragment');

        $this->assertEquals([], $url->getParsedQuery(), 'The parsed query was not changed to an empty array');
        $this->assertEquals('example.com', $url->getHost(), 'The host was not changed to example.com');
        $this->assertEquals('some-cool-fragment', $url->getFragment(), 'The fragment some-cool-fragment was not added to the URL');

        $this->assertEquals('https://user:pass@example.com/uri-part-1#some-cool-fragment', $url->getUrl(), 'The URL was not properly updated after changing fragment, host and query');
    }
}