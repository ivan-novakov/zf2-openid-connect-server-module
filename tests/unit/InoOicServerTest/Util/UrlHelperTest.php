<?php

namespace InoOicServerTest\Util;

use InoOicServer\Util\UrlHelper;


class UrlHelperTest extends \PHPUnit_Framework_TestCase
{

    protected $helper;


    public function setUp()
    {
        $this->helper = new UrlHelper($this->createRouterMock());
    }


    public function testCreateUrlFromRoute()
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
        
        $this->assertSame($uri, $this->helper->createUrlFromRoute($routeName, $params));
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