<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\AuthorizeResponse;


class AuthorizeResponseTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $code = 'testcode';
        $state = 'teststate';
        $sessionId = '123';
        $authSessionId = '456';
        
        $response = new AuthorizeResponse();
        $response->setCode($code);
        $response->setState($state);
        $response->setSessionId($sessionId);
        $response->setAuthSessionId($authSessionId);
        
        $this->assertSame($code, $response->getCode());
        $this->assertSame($state, $response->getState());
        $this->assertSame($sessionId, $response->getSessionId());
        $this->assertSame($authSessionId, $response->getAuthSessionId());
    }
}