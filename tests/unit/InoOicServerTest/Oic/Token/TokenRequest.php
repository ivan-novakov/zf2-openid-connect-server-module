<?php

namespace InoOicServerTest\Oic\Token;

use InoOicServer\Oic\Token\TokenRequest;


class TokenRequestTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $clientId = 'testclient';
        $clientSecret = 'testsecret';
        $redirectUri = 'http://redirect';
        $grantType = 'foo';
        $code = 'testcode';
        
        $tokenRequest = new TokenRequest();
        $tokenRequest->setClientId($clientId);
        $tokenRequest->setClientSecret($clientSecret);
        $tokenRequest->setRedirectUri($redirectUri);
        $tokenRequest->setGrantType($grantType);
        $tokenRequest->getCode();
        
        $this->assertSame($clientId, $tokenRequest->getClientId());
        $this->assertSame($clientSecret, $tokenRequest->getClientSecret());
        $this->assertSame($redirectUri, $tokenRequest->getRedirectUri());
        $this->assertSame($grantType, $tokenRequest->getGrantType());
        $this->assertSame($code, $tokenRequest->getCode());
    }
}