<?php

namespace InoOicServerTest\Oic\AuthCode;

use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Client\Client;
use InoOicServer\Oic\AuthCode\AuthCodeFactory;


class AuthCodeFactoryTest extends \PHPUnit_Framework_TestCase
{


    public function testCreateAuthCode()
    {
        $clientId = 'testclient';
        $sessionId = '123abc';
        $createTime = new \DateTime();
        $expirationTime = new \DateTime();
        $age = 1200;
        $salt = 'secret';
        $scope = 'foo bar';
        $hash = 'auth_code_hash';
        
        $session = new Session();
        $session->setId($sessionId);
        
        $client = new Client();
        $client->setId($clientId);
        
        $hashGenerator = $this->getMock('InoOicServer\Oic\AuthCode\Hash\AuthCodeHashGeneratorInterface');
        $hashGenerator->expects($this->once())
            ->method('generateAuthCodeHash')
            ->with($session, $salt)
            ->will($this->returnValue($hash));
        
        $dtUtil = $this->getMock('InoOicServer\Util\DateTimeUtil');
        $dtUtil->expects($this->once())
            ->method('createDateTime')
            ->will($this->returnValue($createTime));
        $dtUtil->expects($this->once())
            ->method('createExpireDateTime')
            ->with($createTime, $age)
            ->will($this->returnValue($expirationTime));
        
        $factory = new AuthCodeFactory();
        $factory->setHashGenerator($hashGenerator);
        $factory->setDateTimeUtil($dtUtil);
        
        $authCode = $factory->createAuthCode($session, $client, $age, $salt, $scope);
        
        $this->assertInstanceOf('InoOicServer\Oic\AuthCode\AuthCode', $authCode);
        $this->assertSame($hash, $authCode->getCode());
        $this->assertSame($sessionId, $authCode->getSessionId());
        $this->assertSame($clientId, $authCode->getClientId());
        $this->assertSame($createTime, $authCode->getCreateTime());
        $this->assertSame($expirationTime, $authCode->getExpirationTime());
        $this->assertSame($scope, $authCode->getScope());
    }
}