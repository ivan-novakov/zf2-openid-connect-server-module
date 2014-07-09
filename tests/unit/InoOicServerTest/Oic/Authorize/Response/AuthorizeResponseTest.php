<?php

namespace InoOicServerTest\Oic\Authorize\Response;

use InoOicServer\Oic\Authorize\Response\AuthorizeResponse;


class AuthorizeResponseTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $redirectUri = 'https://redirect';
        $code = 'testcode';
        $state = 'teststate';
        $sessionId = '123';
        $authSessionId = '456';
        
        $response = new AuthorizeResponse();
        $response->setRedirectUri($redirectUri);
        $response->setCode($code);
        $response->setState($state);
        $response->setSessionId($sessionId);
        $response->setAuthSessionId($authSessionId);
        
        $this->assertSame($redirectUri, $response->getRedirectUri());
        $this->assertSame($code, $response->getCode());
        $this->assertSame($state, $response->getState());
        $this->assertSame($sessionId, $response->getSessionId());
        $this->assertSame($authSessionId, $response->getAuthSessionId());
    }
}