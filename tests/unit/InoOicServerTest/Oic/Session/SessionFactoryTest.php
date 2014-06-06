<?php

namespace InoOicServerTest\Oic\Session;

use InoOicServer\Oic\Session\SessionFactory;


class SessionFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = new SessionFactory($this->createHashGeneratorMock());
    }


    public function testCreateSession()
    {
        $age = 3600;
        $salt = 'secretsalt';
        $authSessionId = 'auth123';
        $sessionId = 'sess456';
        $nonce = 'secretnonce';
        $createTime = new \DateTime('2014-06-06 14:00:00');
        $expirationTime = new \DateTime('2014-06-06 15:00:00');
        
        $authSession = $this->getMock('InoOicServer\Oic\AuthSession\AuthSession');
        $authSession->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($authSessionId));
        
        $dateTimeUtil = $this->getMock('InoOicServer\Util\DateTimeUtil');
        $dateTimeUtil->expects($this->once())
            ->method('createDateTime')
            ->will($this->returnValue($createTime));
        $dateTimeUtil->expects($this->once())
            ->method('createExpireDateTime')
            ->with($createTime, $age)
            ->will($this->returnValue($expirationTime));
        $this->factory->setDateTimeUtil($dateTimeUtil);
        
        $hashGenerator = $this->createHashGeneratorMock();
        $hashGenerator->expects($this->once())
            ->method('generate')
            ->with(array(
            $authSessionId,
            $createTime->getTimestamp(),
            $salt
        ))
            ->will($this->returnValue($sessionId));
        $this->factory->setHashGenerator($hashGenerator);
        
        $session = $this->factory->createSession($authSession, $age, $salt, $nonce);
        
        $this->assertInstanceOf('InoOicServer\Oic\Session\Session', $session);
    }
    
    /*
     * 
     */
    protected function createHashGeneratorMock()
    {
        $hashGenerator = $this->getMock('InoOicServer\Crypto\Hash\HashGeneratorInterface');
        
        return $hashGenerator;
    }
}