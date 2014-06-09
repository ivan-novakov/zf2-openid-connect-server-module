<?php

namespace InoOicServerTest\Oic\Session\Hash;

use InoOicServer\Oic\AuthSession\AuthSession;


class SessionHashGeneratorTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerateSessionHashWithImplicitAlgo()
    {
        $algo = 'sha123';
        $authSessionId = '123456';
        $time = time();
        $salt = 'secret';
        $hash = 'generated_hash';
        $data = $authSessionId . $time;
        
        $authSession = new AuthSession();
        $authSession->setId($authSessionId);
        $authSession->setCreateTime(new \DateTime('@' . $time));
        
        $generator = $this->getMockBuilder('InoOicServer\Oic\Session\Hash\SessionHashGenerator')
            ->setMethods(array(
            'generateHash'
        ))
            ->getMock();
        $generator->expects($this->once())
            ->method('generateHash')
            ->with($algo, $data, $salt)
            ->will($this->returnValue($hash));
        
        $generator->setDefaultAlgo($algo);
        $this->assertSame($hash, $generator->generateSessionHash($authSession, $salt));
    }
}