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


    public function testCreateSessionWithUnauthenticatedUser()
    {
        $this->setExpectedException('InoOicServer\Oic\Session\Exception\InvalidUserAuthenticationStatusException', 'Cannot create session for unauthenticated user');
        
        $userAuthStatus = $this->createUserAuthStatusMock();
        $userAuthStatus->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(false));
        
        $session = $this->service->createSession($userAuthStatus);
    }


    public function testCreateSessionWithMissingIdentity()
    {
        $this->setExpectedException('InoOicServer\Oic\Session\Exception\InvalidUserAuthenticationStatusException', 'User identity not found');
        
        $userAuthStatus = $this->createUserAuthStatusMock();
        $userAuthStatus->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(true));
        
        $session = $this->service->createSession($userAuthStatus);
    }


    public function testCreateSession()
    {
        $userId = 'testuser';
        $sessionId = '123';
        $authSessionId = '456';
        $authMethod = 'dummy';
        $authTime = new \DateTime('2014-01-01');
        $createTime = new \DateTime('2014-01-02');
        $modifyTime = clone $createTime;
        $sessionAge = 'PT2H';
        
        $expirationTime = clone $createTime;
        $expirationTime->add(new \DateInterval($sessionAge));
        
        $user = $this->getMock('InoOicServer\Oic\User\User');
        $user->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue($userId));
        
        $data = array(
            'id' => $sessionId,
            'authentication_session_id' => $authSessionId,
            'authentication_method' => $authMethod,
            'authentication_time' => $authTime,
            'create_time' => $createTime,
            'modify_time' => $modifyTime,
            'expiration_time' => $expirationTime,
            'user' => $user
        );
        
        $userAuthStatus = $this->createUserAuthStatusMock();
        $userAuthStatus->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(true));
        $userAuthStatus->expects($this->exactly(3))
            ->method('getIdentity')
            ->will($this->returnValue($user));
        $userAuthStatus->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($authMethod));
        $userAuthStatus->expects($this->once())
            ->method('getTime')
            ->will($this->returnValue($authTime));
        
        $generator = $this->createTokenGeneratorMock();
        $generator->expects($this->at(0))
            ->method('generate')
            ->with(array(
            'Session ID',
            $userId
        ))
            ->will($this->returnValue($sessionId));
        $generator->expects($this->at(1))
            ->method('generate')
            ->with(array(
            'Authentication Session ID',
            $userId
        ))
            ->will($this->returnValue($authSessionId));
        $this->service->setTokenGenerator($generator);
        
        $session = $this->getMock('InoOicServer\Oic\Session\Session');
        
        $sessionFactory = $this->createSessionFactoryMock();
        $sessionFactory->expects($this->once())
            ->method('createSession')
            ->will($this->returnValue($session));
        $this->service->setSessionFactory($sessionFactory);
        
        $sessionHydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
        $sessionHydrator->expects($this->once())
            ->method('hydrate')
            ->with($data, $session)
            ->will($this->returnValue($session));
        $this->service->setSessionHydrator($sessionHydrator);
        
        $this->service->setOptions(array(
            'session_age' => $sessionAge
        ));
        
        $this->assertSame($session, $this->service->createSession($userAuthStatus, $createTime));
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
    protected function createTokenGeneratorMock()
    {
        $generator = $this->getMock('InoOicServer\Util\TokenGenerator\TokenGeneratorInterface');
        
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