<?php
namespace InoOicServerTest\Oic\AuthCode;

use DateTime;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Session\Session;

class AuthCodeTest extends \PHPUnit_Framework_TestCase
{

    public function testGettersAndSetters()
    {
        $code = '123';
        $clientId = 'testclient';
        $sessionId = 'asdf';
        $createTime = '2014-01-02';
        $expirationTime = '2014-01-03';
        $scope = 'foo';
        $session = new Session();
        
        $authCode = new AuthCode();
        
        $authCode->setCode($code);
        $authCode->setClientId($clientId);
        $authCode->setSessionId($sessionId);
        $authCode->setCreateTime($createTime);
        $authCode->setExpirationTime($expirationTime);
        $authCode->setScope($scope);
        $authCode->setSession($session);
        
        $this->assertSame($code, $authCode->getCode());
        $this->assertSame($clientId, $authCode->getClientId());
        $this->assertSame($sessionId, $authCode->getSessionId());
        $this->assertEquals(new DateTime($createTime), $authCode->getCreateTime());
        $this->assertEquals(new DateTime($expirationTime), $authCode->getExpirationTime());
        $this->assertSame($scope, $authCode->getScope());
        $this->assertSame($session, $authCode->getSession());
    }
}