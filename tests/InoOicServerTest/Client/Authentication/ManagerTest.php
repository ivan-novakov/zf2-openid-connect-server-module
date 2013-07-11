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


    public function testConstructor()
    {
        $options = array(
            'foo' => 'bar'
        );
        $manager = new Manager($options);
        $this->assertSame($options, (array) $manager->getOptions());
    }


    public function testSetOptions()
    {
        $options = array(
            'foo' => 'bar'
        );
        $this->manager->setOptions($options);
        $this->assertSame($options, (array) $this->manager->getOptions());
    }


    public function testGetAuthenticationMethodFactoryWithImplicitValue()
    {
        $factory = $this->manager->getAuthenticationMethodFactory();
        $this->assertInstanceOf('InoOicServer\Client\Authentication\Method\MethodFactoryInterface', $factory);
    }


    public function testAuthenticateWithInvalidMethod()
    {
        $authenticationMethod = 'invalid_auth_method';
        
        $this->setExpectedException(
            'InoOicServer\Client\Authentication\Method\Exception\InvalidAuthenticationMethodException', 
            "Invalid client authentication method '$authenticationMethod'");
        
        $client = $this->createClientMock($authenticationMethod);
        $request = $this->createRequestMock();
        
        $this->manager->authenticate($request, $client);
    }


    public function testAuthenticate()
    {
        $authenticationMethod = 'dummy';
        $methodConfig = array(
            'class' => 'Dummy'
        );
        $options = array(
            'methods' => array(
                $authenticationMethod => $methodConfig
            )
        );
        
        $httpRequest = $this->getMock('Zend\Http\Request');
        
        $result = $this->getMockBuilder('InoOicServer\Client\Authentication\Result');
        
        $clientAuthenticationInfo = $this->createInfoMock($authenticationMethod);
        
        $client = $this->createClientMock($authenticationMethod, $clientAuthenticationInfo);
        $request = $this->createRequestMock();
        $request->expects($this->once())
            ->method('getHttpRequest')
            ->will($this->returnValue($httpRequest));
        
        $method = $this->getMock('InoOicServer\Client\Authentication\Method\MethodInterface');
        $method->expects($this->once())
            ->method('authenticate')
            ->with($clientAuthenticationInfo, $httpRequest)
            ->will($this->returnValue($result));
        
        $methodFactory = $this->getMockBuilder('InoOicServer\Client\Authentication\Method\MethodFactoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $methodFactory->expects($this->any())
            ->method('createAuthenticationMethod')
            ->with($methodConfig)
            ->will($this->returnValue($method));
        
        $this->manager->setOptions($options);
        $this->manager->setAuthenticationMethodFactory($methodFactory);
        
        $this->assertSame($result, $this->manager->authenticate($request, $client));
    }
    
    /*
     * --------------------------
     */
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createRequestMock()
    {
        $request = $this->getMock('InoOicServer\OpenIdConnect\Request\RequestInterface');
        return $request;
    }


    protected function createClientMock($authenticationMethod, $info = null)
    {
        if (! $info) {
            $info = $this->createInfoMock($authenticationMethod);
        }
        
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