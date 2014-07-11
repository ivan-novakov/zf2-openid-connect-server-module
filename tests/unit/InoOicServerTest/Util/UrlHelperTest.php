<?php

namespace InoOicServerTest\Util;

use Zend\Mvc\Router\Http\TreeRouteStack;
use InoOicServer\Util\UrlHelper;


class UrlHelperTest extends \PHPUnit_Framework_TestCase
{

    protected $helper;


    public function setUp()
    {
        $this->helper = new UrlHelper($this->createRouterMock());
    }


    /**
     * FIXME: write also an integration test with the real router implementation.
     */
    public function testCreateUriFromRoute()
    {
        $routeName = 'dummy';
        $params = array(
            'foo' => 'bar'
        );
        $options = array(
            'name' => $routeName
        );
        $path = '/auth/path';
        
        $uri = $this->getMock('Zend\Uri\Http');
        $uri->expects($this->once())
            ->method('setPath')
            ->with($path);
        
        $router = $this->createRouterMock();
        $router->expects($this->once())
            ->method('assemble')
            ->with($params, $options)
            ->will($this->returnValue($path));
        $router->expects($this->once())
            ->method('getRequestUri')
            ->will($this->returnValue($uri));
        $this->helper->setRouter($router);
        
        $this->assertSame($uri, $this->helper->createUriFromRoute($routeName, $params));
    }


    public function testCreateUrlStringFromRoute()
    {
        $routeName = 'foo';
        $params = array(
            'key' => 'value'
        );
        $url = 'https://url/string';
        
        $uri = $this->getMock('Zend\Uri\Http');
        $uri->expects($this->once())
            ->method('toString')
            ->will($this->returnValue($url));
        
        $helper = $this->getMockBuilder('InoOicServer\Util\UrlHelper')
            ->setMethods(array(
            'createUriFromRoute'
        ))
            ->disableOriginalConstructor()
            ->getMock();
        
        $helper->expects($this->once())
            ->method('createUriFromRoute')
            ->with($routeName, $params)
            ->will($this->returnValue($uri));
        
        $this->assertSame($url, $helper->createUrlStringFromRoute($routeName, $params));
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createRouterMock()
    {
        $router = $this->getMockBuilder('Zend\Mvc\Router\Http\TreeRouteStack')->getMock();
        
        return $router;
    }
}