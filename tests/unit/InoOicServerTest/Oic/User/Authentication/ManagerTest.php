<?php

namespace InoOicServerTest\Oic\User\Authentication;

use InoOicServer\Oic\User\Authentication\Manager;


class ManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Manager
     */
    protected $manager;


    public function setUp()
    {
        $this->manager = new Manager(array(), $this->createRouterMock());
    }


    public function testGetAuthenticationMethod()
    {
        $method = 'foo';
        $this->manager->setOptions(array(
            Manager::OPT_METHOD => $method
        ));
        
        $this->assertSame($method, $this->manager->getAuthenticationMethod());
    }


    public function testGetAuthenticationUrlWithMissingMethod()
    {
        $this->setExpectedException('InoOicServer\Exception\MissingOptionException', "Missing option 'method'");
        
        $this->manager->getAuthenticationUrl();
    }


    public function testGetAuthenticationUrlWithMissingAuthRoute()
    {
        $this->setExpectedException('InoOicServer\Exception\MissingOptionException', "Missing option 'auth_route'");
        
        $this->manager->setOptions(array(
            Manager::OPT_METHOD => 'foo'
        ));
        
        $this->manager->getAuthenticationUrl();
    }


    public function testGetAuthenticationUrl()
    {
        $method = 'foo';
        $authRoute = 'bar';
        
        $params = array(
            'controller' => $method
        );
        $options = array(
            'name' => $authRoute
        );
        
        $url = 'https://auth/url';
        
        $router = $this->createRouterMock();
        $router->expects($this->once())
            ->method('assemble')
            ->with($params, $options)
            ->will($this->returnValue($url));
        $this->manager->setRouter($router);
        $this->manager->setOptions(array(
            Manager::OPT_METHOD => $method,
            Manager::OPT_AUTH_ROUTE => $authRoute
        ));
        
        $this->assertSame($url, $this->manager->getAuthenticationUrl());
    }


    public function testGetReturnUrlWithMissingRoute()
    {
        $this->setExpectedException('InoOicServer\Exception\MissingOptionException', "Missing option 'return_route'");
        
        $this->manager->getReturnUrl();
    }


    public function testGetReturnUrl()
    {
        $returnRoute = 'foo';
        $params = array();
        $options = array(
            'name' => $returnRoute
        );
        $url = 'https://return/url';
        
        $router = $this->createRouterMock();
        $router->expects($this->once())
            ->method('assemble')
            ->with($params, $options)
            ->will($this->returnValue($url));
        
        $this->manager->setRouter($router);
        $this->manager->setOptions(array(
            Manager::OPT_RETURN_ROUTE => $returnRoute
        ));
        
        $this->assertSame($url, $this->manager->getReturnUrl());
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