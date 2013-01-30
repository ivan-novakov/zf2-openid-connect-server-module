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


    public function testAuthenticate()
    {
        $info = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue('dummy'));
        
        $client = $this->getMock('PhpIdServer\Client\Client');
        $client->expects($this->once())
            ->method('getAuthenticationInfo')
            ->will($this->returnValue($info));
        
        $request = $this->getMock('PhpIdServer\OpenIdConnect\Request\ClientRequestInterface');
        
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