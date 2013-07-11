<?php

namespace InoOicServerTest\Client\Authentication;

use InoOicServer\Client\Authentication\Manager;


class ManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Manager
     */
    protected $manager = null;


    public function setUp()
    {
        $this->manager = new Manager();
    }


    public function testAuthenticateWithDummy()
    {
        $authenticationMethod = 'dummy';
        
        $info = $this->getMockBuilder('InoOicServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        $request = $this->createRequestMock();
        $client = $this->createClientMock($authenticationMethod);
        
        $result = $this->manager->authenticate($request, $client);
        $this->assertTrue($result->isAuthenticated());
    }


    public function testAuthenticate()
    {
        $authenticationMethod = 'dummy';
        
        $client = $this->createClientMock($authenticationMethod);
        $request = $this->createRequestMock();
        
        $method = $this->getMock('InoOicServer\Client\Authentication\Method\MethodInterface');
        
        $methodFactory = $this->getMockBuilder('InoOicServer\Client\Authentication\Method\MethodFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $methodFactory->expects($this->any())
            ->method('createMethod')
            ->will($this->returnValue($method));
        
        $this->manager->setAuthenticationMethodFactory($methodFactory);
        $this->manager->authenticate($request, $client);
    }
    
    /*
     * --------------------------
     */


    protected function createRequestMock()
    {
        $request = $this->getMock('InoOicServer\OpenIdConnect\Request\RequestInterface');
        return $request;
    }


    protected function createClientMock($authenticationMethod)
    {
        $info = $this->createInfoMock($authenticationMethod);
        
        $client = $this->getMock('InoOicServer\Client\Client');
        $client->expects($this->once())
            ->method('getAuthenticationInfo')
            ->will($this->returnValue($info));
        
        return $client;
    }


    protected function createInfoMock($authenticationMethod)
    {
        $info = $this->getMockBuilder('InoOicServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        return $info;
    }
}