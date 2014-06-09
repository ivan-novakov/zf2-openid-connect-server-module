<?php

namespace InoOicServerTest\Oic\AuthSession\Hash;

use InoOicServer\Oic\User\Authentication\Status;


class AuthSessionHashGeneratorTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerateHashWithImplicitAlgo()
    {
        $algo = 'sha123';
        $time = time();
        $userId = 'testuser';
        $salt = 'secret';
        $hash = 'generated_hash';
        $data = $userId . $time;
        
        $user = $this->getMock('InoOicServer\Oic\User\UserInterface');
        $user->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($userId));
        
        $authStatus = new Status();
        $authStatus->setIdentity($user);
        $authStatus->setTime(new \DateTime('@' . $time));
        
        $generator = $this->getMockBuilder('InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGenerator')
            ->setMethods(array(
            'generateHash'
        ))
            ->getMock();
        
        $generator->expects($this->once())
            ->method('generateHash')
            ->with($algo, $data, $salt)
            ->will($this->returnValue($hash));
        
        $generator->setDefaultAlgo($algo);
        
        $this->assertSame($hash, $generator->generateAuthSessionHash($authStatus, $salt));
    }
}