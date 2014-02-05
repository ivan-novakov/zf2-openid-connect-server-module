<?php

namespace InoOicServerTest\Oic\Authorize\Request;

use InoOicServer\Oic\Authorize\Request\Request;


class RequestTest extends \PHPUnit_Framework_Testcase
{


    public function testSettersAndGetters()
    {
        $clientId = 'testclient';
        $redirectUri = 'http://dummy/';
        $state = 'abc123';
        $responseType = 'dummy';
        $nonce = 'zzz';
        
        $request = new Request();
        $request->setClientId($clientId);
        $request->setRedirectUri($redirectUri);
        $request->setState($state);
        $request->setResponseType($responseType);
        $request->setNonce($nonce);
        
        $this->assertSame($clientId, $request->getClientId());
        $this->assertSame($redirectUri, $request->getRedirectUri());
        $this->assertSame($state, $request->getState());
        $this->assertSame($responseType, $request->getResponseType());
        $this->assertSame($nonce, $request->getNonce());
    }
}