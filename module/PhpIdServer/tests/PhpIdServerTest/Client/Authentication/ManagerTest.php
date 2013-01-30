<?php

namespace PhpIdServerTest\Client\Authentication;

use PhpIdServer\Client\Authentication\Manager;


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


    public function testAuthenticateInvalidMethod()
    {
        $authenticationMethod1 = 'dummy1';
        $authenticationMethod2 = 'dummy2';
        
        $info = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod1));
        
        $client = $this->getMock('PhpIdServer\Client\Client');
        $client->expects($this->once())
            ->method('getAuthenticationInfo')
            ->will($this->returnValue($info));
        
        $data = $this->getMockBuilder('PhpIdServer\Client\Authentication\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $data->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod2));
        
        $request = $this->getMock('PhpIdServer\OpenIdConnect\Request\ClientRequestInterface');
        $request->expects($this->once())
            ->method('getAuthenticationData')
            ->will($this->returnValue($data));
        
        $result = $this->manager->authenticate($request, $client);
        $this->assertFalse($result->isAuthenticated());
    }


    public function testAuthenticate()
    {
        $authenticationMethod = 'dummy';
        
        $info = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        $client = $this->getMock('PhpIdServer\Client\Client');
        $client->expects($this->once())
            ->method('getAuthenticationInfo')
            ->will($this->returnValue($info));
        
        $data = $this->getMockBuilder('PhpIdServer\Client\Authentication\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $data->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        $request = $this->getMock('PhpIdServer\OpenIdConnect\Request\ClientRequestInterface');
        $request->expects($this->once())
            ->method('getAuthenticationData')
            ->will($this->returnValue($data));
        
        $method = $this->getMock('PhpIdServer\Client\Authentication\Method\MethodInterface');
        
        $methodFactory = $this->getMockBuilder('PhpIdServer\Client\Authentication\Method\MethodFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $methodFactory->expects($this->once())
            ->method('createMethod')
            ->will($this->returnValue($method));
        
        $this->manager->setAuthenticationMethodFactory($methodFactory);
        $this->manager->authenticate($request, $client);
    }
}