<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\AuthorizeRequest;


class AuthorizeRequestTest extends \PHPUnit_Framework_Testcase
{


    public function testSettersAndGetters()
    {
        $clientId = 'testclient';
        $redirectUri = 'http://dummy/';
        $state = 'abc123';
        $responseType = 'dummy';
        $scope = 'foo';
        $authSessionId = 'asd456';
        
        $request = new AuthorizeRequest();
        $request->setClientId($clientId);
        $request->setRedirectUri($redirectUri);
        $request->setState($state);
        $request->setResponseType($responseType);
        $request->setState($state);
        $request->setAuthenticationSessionId($authSessionId);
        
        $this->assertSame($clientId, $request->getClientId());
        $this->assertSame($redirectUri, $request->getRedirectUri());
        $this->assertSame($state, $request->getState());
        $this->assertSame($responseType, $request->getResponseType());
        $this->assertSame($state, $request->getState());
        $this->assertSame($authSessionId, $request->getAuthenticationSessionId());
    }
}