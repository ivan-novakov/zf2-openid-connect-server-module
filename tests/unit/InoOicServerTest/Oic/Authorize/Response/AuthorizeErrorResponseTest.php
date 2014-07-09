<?php

namespace InoOicServerTest\Oic\Authorize\Response;

use InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse;


class AuthorizeErrorResponseTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $redirectUri = 'https://redirect';
        $state = 'foo';
        $error = $this->getMock('InoOicServer\Oic\Error');
        
        $response = new AuthorizeErrorResponse();
        $response->setRedirectUri($redirectUri);
        $response->setState($state);
        $response->setError($error);
        
        $this->assertSame($redirectUri, $response->getRedirectUri());
        $this->assertSame($state, $response->getState());
        $this->assertSame($error, $response->getError());
    }
}