<?php

namespace InoOicServerTest\Oic\Session;

use InoOicServer\Oic\Session\SessionFactory;


class SessionFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = new SessionFactory();
    }


    public function testCreateEmptySession()
    {
        $this->assertInstanceOf('InoOicServer\Oic\Session\Session', $this->factory->createEmptySession());
    }


    public function testCreateSessionWithUnauthenticatedUser()
    {
        $this->setExpectedException('InoOicServer\Oic\Session\Exception\InvalidUserAuthenticationStatusException', 'Cannot create session for unauthenticated user');
        
        $userAuthStatus = $this->createUserAuthStatusMock();
        $userAuthStatus->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(false));
        
        $session = $this->factory->createSession($userAuthStatus);
    }


    public function testCreateSessionWithMissingIdentity()
    {
        $this->setExpectedException('InoOicServer\Oic\Session\Exception\InvalidUserAuthenticationStatusException', 'User identity not found');
        
        $userAuthStatus = $this->createUserAuthStatusMock();
        $userAuthStatus->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(true));
        
        $session = $this->factory->createSession($userAuthStatus);
    }


    public function testCreateSession()
    {
        $userId = 'testuser';
        $sessionId = '123';
        $authSessionId = '456';
        $authMethod = 'dummy';
        $authTime = new \DateTime('2014-01-01');
        $createTime = new \DateTime('2014-01-02');
        $sessionAge = 'PT2H';
        
        $expirationTime = clone $createTime;
        $expirationTime->add(new \DateInterval($sessionAge));
        
        $user = $this->getMock('InoOicServer\Oic\User\User');
        $user->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue($userId));
        
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
        
        $generator = $this->getMock('InoOicServer\Util\TokenGenerator\TokenGeneratorInterface');
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
        $this->factory->setTokenGenerator($generator);
        
        $this->factory->setOptions(array(
            'session_age' => $sessionAge
        ));
        $session = $this->factory->createSession($userAuthStatus, $createTime);
        
        $this->assertInstanceOf('InoOicServer\Oic\Session\Session', $session);
        $this->assertSame($sessionId, $session->getId());
        $this->assertSame($authSessionId, $session->getAuthenticationSessionId());
        $this->assertSame($authMethod, $session->getAuthenticationMethod());
        $this->assertSame($authTime, $session->getAuthenticationTime());
        $this->assertSame($createTime, $session->getCreateTime());
        $this->assertNotSame($createTime, $session->getModifyTime());
        $this->assertEquals($createTime, $session->getModifyTime());
        $this->assertEquals($expirationTime, $session->getExpirationTime());
    }
    
    /*
     * 
     */
    protected function createUserAuthStatusMock()
    {
        $userAuthStatus = $this->getMock('InoOicServer\Oic\User\Authentication\Status');
        
        return $userAuthStatus;
    }
}