<?php

namespace InoOicServerTest\Oic\AuthCode\Hash;

use InoOicServer\Oic\Session\Session;


class AuthCodeHashGenerator extends \PHPUnit_Framework_TestCase
{


    public function testGenerateAuthCodeHash()
    {
        $sessionId = '123abc';
        $salt = 'secret';
        $time = time();
        $algo = 'sha123';
        $data = $sessionId . $time;
        $hash = 'qwerty';
        
        $session = new Session();
        $session->setId($sessionId);
        $session->setCreateTime(new \DateTime('@' . $time));
        
        $generator = $this->getMockBuilder('InoOicServer\Oic\AuthCode\Hash\AuthCodeHashGenerator')
            ->setMethods(array(
            'generateHash'
        ))
            ->getMock();
        $generator->expects($this->once())
            ->method('generateHash')
            ->with($data, $salt, $algo)
            ->will($this->returnValue($hash));
        $generator->setDefaultAlgo($algo);

        $this->assertSame($hash, $generator->generateAuthCodeHash($session, $salt, $algo));
    }
}