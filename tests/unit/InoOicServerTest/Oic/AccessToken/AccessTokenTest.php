<?php

namespace InoOicServerTest\Oic\AccessToken;

use DateTime;
use InoOicServer\Oic\AccessToken\AccessToken;


class AccessTokenTest extends \PHPUnit_Framework_TestCase
{


    public function testGettersAndSetters()
    {
        $tokenString = '123qwe';
        $clientId = 'testclient';
        $sessionId = 'qwerty';
        $createTime = '2014-01-02';
        $expirationTime = '2014-01-03';
        $type = 'unknown';
        $scope = 'foo';
        
        $token = new AccessToken();
        
        $token->setToken($tokenString);
        $token->setClientId($clientId);
        $token->setSessionId($sessionId);
        $token->setCreateTime($createTime);
        $token->setExpirationTime($expirationTime);
        $token->setType($type);
        $token->setScope($scope);
        
        $this->assertSame($tokenString, $token->getToken());
        $this->assertSame($clientId, $token->getClientId());
        $this->assertSame($sessionId, $token->getSessionId());
        $this->assertEquals(new DateTime($createTime), $token->getCreateTime());
        $this->assertEquals(new DateTime($expirationTime), $token->getExpirationTime());
        $this->assertSame($type, $token->getType());
        $this->assertSame($scope, $token->getScope());
    }
}