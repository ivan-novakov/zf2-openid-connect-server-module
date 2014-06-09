<?php

namespace InoOicServerTest\Oic\AuthSession;

use InoOicServer\Oic\AuthSession\AuthSessionFactory;
use InoOicServer\Oic\User\Authentication\Status;


class AuthSessionFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = new AuthSessionFactory($this->createHashGeneratorMock());
    }


    public function testCreateAuthSessionWithNoAuthentication()
    {
        $this->setExpectedException('InoOicServer\Oic\AuthSession\Exception\UnauthenticatedUserException', 'Unauthenticated user');
        
        $age = 120;
        $salt = 'testsalt';
        
        $authStatus = new Status();
        $this->factory->createAuthSession($authStatus, $age, $salt);
    }


    public function testCreateAuthSessionWithMissingIdentity()
    {
        $this->setExpectedException('InoOicServer\Oic\AuthSession\Exception\UnknownIdentityException', 'Missing user identity ');
        
        $age = 120;
        $salt = 'testsalt';
        
        $authStatus = new Status();
        $authStatus->setAuthenticated(true);
        $this->factory->createAuthSession($authStatus, $age, $salt);
    }


    public function testCreateAuthSessionWithAgeAndSaltFromArguments()
    {
        $userId = 'testuser';
        $method = 'dummy';
        $time = new \DateTime('2014-06-05 10:00:00');
        $salt = 'secretsalt';
        $age = 120;
        $user = $this->createUserMock($userId);
        $hash = 'resulting_hash';
        
        $authStatus = new Status();
        $authStatus->setAuthenticated(true);
        $authStatus->setIdentity($user);
        $authStatus->setMethod($method);
        $authStatus->setTime($time);
        
        $hashGenerator = $this->createHashGeneratorMock();
        $hashGenerator->expects($this->once())
            ->method('generateAuthSessionHash')
            ->with($authStatus, $salt)
            ->will($this->returnValue($hash));
        $this->factory->setHashGenerator($hashGenerator);
        
        $authSession = $this->factory->createAuthSession($authStatus, $age, $salt);
        
        $this->assertSame($hash, $authSession->getId());
        $this->assertSame($method, $authSession->getMethod());
        $this->assertSame($time, $authSession->getCreateTime());
        $this->assertEquals(new \DateTime('2014-06-05 10:02:00'), $authSession->getExpirationTime());
        $this->assertSame($user, $authSession->getUser());
    }
    
    /*
     * 
     */
    protected function createHashGeneratorMock()
    {
        $generator = $this->getMock('InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGeneratorInterface');
        
        return $generator;
    }


    protected function createUserMock($userId = null)
    {
        $user = $this->getMock('InoOicServer\Oic\User\UserInterface');
        if ($userId) {
            $user->expects($this->any())
                ->method('getId')
                ->will($this->returnValue($userId));
        }
        
        return $user;
    }
}