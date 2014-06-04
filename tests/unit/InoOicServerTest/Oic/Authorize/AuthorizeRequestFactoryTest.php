<?php

namespace InoOicServerTest\Oic\Authorize;

use Zend\Http\Header\Cookie;
use Zend\Http;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactory;


class AuthorizeRequestFactoryTest extends \PHPUnit_Framework_TestCase
{


    public function testSetOptions()
    {
        $authCookieName = 'foo';
        $sessionCookieName = 'bar';
        
        $factory = new AuthorizeRequestFactory(array(
            'auth_cookie_name' => $authCookieName,
            'session_cookie_name' => $sessionCookieName
        ));
        
        $this->assertSame($authCookieName, $factory->getOption('auth_cookie_name'));
        $this->assertSame($sessionCookieName, $factory->getOption('session_cookie_name'));
    }


    public function testCreateRequest()
    {
        $authCookieName = 'foocookie';
        $authSessionId = '123abc';
        $sessionCookieName = 'barcookie';
        $sessionId = '456asd';
        
        $params = array(
            'client_id' => 'testclient',
            'redirect_uri' => 'https://redirect/',
            'response_type' => 'foo',
            'scope' => 'bar',
            'state' => '123456',
            'nonce' => 'testnonce'
        );
        
        $httpRequest = new Http\Request();
        $httpRequest->getQuery()->fromArray($params);
        $httpRequest->getHeaders()->addHeader(new Cookie(array(
            $authCookieName => $authSessionId,
            $sessionCookieName => $sessionId
        )));
        
        $factory = new AuthorizeRequestFactory(array(
            AuthorizeRequestFactory::OPT_AUTH_COOKIE_NAME => $authCookieName,
            AuthorizeRequestFactory::OPT_SESSION_COOKIE_NAME => $sessionCookieName
        ));
        $request = $factory->createRequest($httpRequest);
        
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\AuthorizeRequest', $request);
        $this->assertSame($params['client_id'], $request->getClientId());
        $this->assertSame($params['redirect_uri'], $request->getRedirectUri());
        $this->assertSame($params['response_type'], $request->getResponseType());
        $this->assertSame($params['scope'], $request->getScope());
        $this->assertSame($params['state'], $request->getState());
        $this->assertSame($params['nonce'], $request->getNonce());
        
        $this->assertSame($httpRequest, $request->getHttpRequest());
        
        $this->assertSame($authSessionId, $request->getAuthenticationSessionId());
        $this->assertSame($sessionId, $request->getSessionId());
    }
}