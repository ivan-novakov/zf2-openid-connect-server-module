<?php

namespace InoOicServerTest\Oic\Authorize\Response;

use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Authorize\AuthorizeRequest;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Authorize\Response\ResponseFactory;


class ResponseFactoryTest extends \PHPUnit_Framework_TestCase
{


    public function testCreateAuthorizeResponse()
    {
        $code = '12345';
        $redirectUri = 'https://redirect';
        $state = 'foo';
        $sessionId = '123';
        $authSessionId = '456';
        
        $authCode = new AuthCode();
        $authCode->setCode($code);
        
        $request = new AuthorizeRequest();
        $request->setRedirectUri($redirectUri);
        $request->setState($state);
        
        $session = new Session();
        $session->setId($sessionId);
        $session->setAuthSessionId($authSessionId);
        
        $factory = new ResponseFactory();
        
        $response = $factory->createAuthorizeResponse($authCode, $request, $session);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Response\AuthorizeResponse', $response);
        $this->assertSame($code, $response->getCode());
        $this->assertSame($redirectUri, $response->getRedirectUri());
        $this->assertSame($state, $response->getState());
        $this->assertSame($sessionId, $response->getSessionId());
        $this->assertSame($authSessionId, $response->getAuthSessionId());
    }


    public function testCreateAuthorizeErrorResponse()
    {
        $error = $this->getMock('InoOicServer\Oic\Error');
        $redirectUri = 'https://redirect';
        $state = 'foo';
        
        $request = new AuthorizeRequest();
        $request->setRedirectUri($redirectUri);
        $request->setState($state);
        
        $factory = new ResponseFactory();
        $response = $factory->createAuthorizeErrorResponse($error, $request);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse', $response);
        $this->assertSame($error, $response->getError());
        $this->assertSame($redirectUri, $response->getRedirectUri());
        $this->assertSame($state, $response->getState());
    }


    public function testCreateClientErrorResponse()
    {
        $error = $this->getMock('InoOicServer\Oic\Error');
        
        $factory = new ResponseFactory();
        $response = $factory->createClientErrorResponse($error);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Response\ClientErrorResponse', $response);
        $this->assertSame($error, $response->getError());
    }
}