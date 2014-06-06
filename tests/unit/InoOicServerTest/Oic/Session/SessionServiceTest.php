<?php

namespace InoOicServerTest\Oic\Session;

use InoOicServer\Oic\Session\SessionService;


class SessionServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $service;


    public function setUp()
    {
        $this->service = new SessionService($this->createSessionMapperMock());
    }


    public function testCreateSession()
    {
        $age = 3600;
        $salt = 'secretsalt';
        $authSession = $this->getMock('InoOicServer\Oic\AuthSession\AuthSession');
        $nonce = 'asd123';
        
        $session = $this->getMock('InoOicServer\Oic\Session\Session');
        
        $factory = $this->createSessionFactoryMock();
        $factory->expects($this->once())
            ->method('createSession')
            ->with($authSession, $age, $salt, $nonce)
            ->will($this->returnValue($session));
        $this->service->setSessionFactory($factory);
        
        $this->service->setOptions(array(
            'age' => $age,
            'salt' => $salt
        ));
        
        $this->assertSame($session, $this->service->createSession($authSession, $nonce));
    }
    
    /*
     * 
     */
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUserAuthStatusMock()
    {
        $userAuthStatus = $this->getMock('InoOicServer\Oic\User\Authentication\Status');
        
        return $userAuthStatus;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSessionMapperMock()
    {
        $mapper = $this->getMock('InoOicServer\Oic\Session\Mapper\MapperInterface');
        
        return $mapper;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createHashGeneratorMock()
    {
        $generator = $this->getMock('InoOicServer\Crypto\Hash\HashGeneratorInterface');
        
        return $generator;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSessionFactoryMock()
    {
        $factory = $this->getMock('InoOicServer\Oic\Session\SessionFactoryInterface');
        
        return $factory;
    }
}