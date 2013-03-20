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


    public function testAuthenticateWithDummy()
    {
        $authenticationMethod = 'dummy';
        
        $info = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        $request = $this->_getRequestMock($authenticationMethod);
        $client = $this->_getClientMock($authenticationMethod);
        
        $result = $this->manager->authenticate($request, $client);
        $this->assertTrue($result->isAuthenticated());
    }


    public function testAuthenticateInvalidMethod()
    {
        $authenticationMethod1 = 'dummy1';
        $authenticationMethod2 = 'dummy2';
        
        $client = $this->_getClientMock($authenticationMethod1);
        $request = $this->_getRequestMock($authenticationMethod2);
        
        $result = $this->manager->authenticate($request, $client);
        $this->assertFalse($result->isAuthenticated());
    }


    public function testAuthenticate()
    {
        $authenticationMethod = 'dummy';
        
        $client = $this->_getClientMock($authenticationMethod);
        $request = $this->_getRequestMock($authenticationMethod);
        
        $method = $this->getMock('PhpIdServer\Client\Authentication\Method\MethodInterface');
        
        $methodFactory = $this->getMockBuilder('PhpIdServer\Client\Authentication\Method\MethodFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $methodFactory->expects($this->any())
            ->method('createMethod')
            ->will($this->returnValue($method));
        
        $this->manager->setAuthenticationMethodFactory($methodFactory);
        $this->manager->authenticate($request, $client);
    }


    protected function _getRequestMock($authenticationMethod)
    {
        $data = $this->_getDataMock($authenticationMethod);
        
        $request = $this->getMock('PhpIdServer\OpenIdConnect\Request\ClientRequestInterface');
        $request->expects($this->any())
            ->method('getAuthenticationData')
            ->will($this->returnValue($data));
        
        return $request;
    }


    protected function _getDataMock($authenticationMethod)
    {
        $data = $this->getMockBuilder('PhpIdServer\Client\Authentication\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $data->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        return $data;
    }


    protected function _getClientMock($authenticationMethod)
    {
        $info = $this->_getInfoMock($authenticationMethod);
        
        $client = $this->getMock('PhpIdServer\Client\Client');
        $client->expects($this->once())
            ->method('getAuthenticationInfo')
            ->will($this->returnValue($info));
        
        return $client;
    }


    protected function _getInfoMock($authenticationMethod)
    {
        $info = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($authenticationMethod));
        
        return $info;
    }
}