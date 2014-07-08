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
        $authSession = $this->createAuthSessionMock();
        $nonce = 'asd123';
        
        $session = $this->createSessionMock();
        
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


    public function testFetchSession()
    {
        $sessionId = '456zxc';
        $session = $this->createSessionMock();
        
        $mapper = $this->createSessionMapperMock();
        $mapper->expects($this->once())
            ->method('fetch')
            ->with($sessionId)
            ->will($this->returnValue($session));
        $this->service->setSessionMapper($mapper);
        
        $this->assertSame($session, $this->service->fetchSession($sessionId));
    }


    public function testFetchSessionByAuthSession()
    {
        $authSessionId = '123asd';
        $authSession = $this->createAuthSessionMock();
        $authSession->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($authSessionId));
        
        $session = $this->createSessionMock();
        $mapper = $this->createSessionMapperMock();
        $mapper->expects($this->once())
            ->method('fetchByAuthSessionId')
            ->with($authSessionId)
            ->will($this->returnValue($session));
        
        $this->service->setSessionMapper($mapper);
        
        $this->assertSame($session, $this->service->fetchSessionByAuthSession($authSession));
    }


    public function testInitSessionFromAuthSessionWithNewSession()
    {
        $session = $this->createSessionMock();
        $authSession = $this->createAuthSessionMock();
        $nonce = 'foo';
        
        $service = $this->getMockBuilder('InoOicServer\Oic\Session\SessionService')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'fetchSessionByAuthSession',
            'createSession',
            'saveSession'
        ))
            ->getMock();
        
        $service->expects($this->once())
            ->method('fetchSessionByAuthSession')
            ->with($authSession)
            ->will($this->returnValue(null));
        
        $service->expects($this->once())
            ->method('createSession')
            ->with($authSession, $nonce)
            ->will($this->returnValue($session));
        
        $service->expects($this->once())
            ->method('saveSession')
            ->with($session);
        
        $this->assertSame($session, $service->initSessionFromAuthSession($authSession, $nonce));
    }


    public function testInitSessionFromAuthSessionWithExistingSession()
    {
        $session = $this->createSessionMock();
        $authSession = $this->createAuthSessionMock();
        $nonce = 'foo';
        
        $service = $this->getMockBuilder('InoOicServer\Oic\Session\SessionService')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'fetchSessionByAuthSession'
        ))
            ->getMock();
        
        $service->expects($this->once())
            ->method('fetchSessionByAuthSession')
            ->with($authSession)
            ->will($this->returnValue($session));
        
        $this->assertSame($session, $service->initSessionFromAuthSession($authSession, $nonce));
    }
    
    /*
     * 
     */
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSessionMock()
    {
        $session = $this->getMock('InoOicServer\Oic\Session\Session');
        
        return $session;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAuthSessionMock()
    {
        $authSession = $this->getMock('InoOicServer\Oic\AuthSession\AuthSession');
        
        return $authSession;
    }


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