<?php
namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\AuthorizeRequestFactory;

class AuthorizeRequestFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateRequest()
    {
        $values = array(
            'client_id' => 'testclient',
            'redirect_uri' => 'https://redirect/',
            'response_type' => 'foo',
            'scope' => 'bar',
            'state' => '123456',
            'nonce' => 'testnonce',
            'session_id' => '456asd',
            'authentication_session_id' => '123abc',
            'http_request' => $this->getMock('Zend\Http\Request')
        );

        $factory = new AuthorizeRequestFactory();
        $request = $factory->createRequest($values);

        $this->assertInstanceOf('InoOicServer\Oic\Authorize\AuthorizeRequest', $request);
        $this->assertSame($values['client_id'], $request->getClientId());
        $this->assertSame($values['redirect_uri'], $request->getRedirectUri());
        $this->assertSame($values['response_type'], $request->getResponseType());
        $this->assertSame($values['scope'], $request->getScope());
        $this->assertSame($values['state'], $request->getState());
        $this->assertSame($values['nonce'], $request->getNonce());

        $this->assertSame($values['authentication_session_id'], $request->getAuthenticationSessionId());
        $this->assertSame($values['session_id'], $request->getSessionId());

        $this->assertSame($values['http_request'], $request->getHttpRequest());
    }
}