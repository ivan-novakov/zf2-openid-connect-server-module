<?php

namespace PhpIdServerTest\OpenIdConnect\Dispatcher;

use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Client\Client;
use MyUnit\Framework\DispatcherTestCase;
use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\OpenIdConnect\Response;


class TokenTest extends DispatcherTestCase
{
    
    /**
     * The dispatcher object.
     * 
     * @var Dispatcher\Token
     */
    protected $_dispatcher = NULL;


    public function setUp ()
    {
        $this->_dispatcher = new Dispatcher\Token();
    }


    public function testDispatchRequestInvalid ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub(true));
        $this->_dispatcher->setTokenResponse($this->_getResponseStub(Response\Token::ERROR_INVALID_REQUEST));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchInvalidClient ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub(Response\Token::ERROR_INVALID_CLIENT));
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchNoAuthorizationCode ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub(Response\Token::ERROR_INVALID_GRANT));
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        $this->_dispatcher->setSessionManager($this->_getSessionManagerStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchExpiredAuthorizationCode ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub(Response\Token::ERROR_INVALID_GRANT_EXPIRED));
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        $this->_dispatcher->setSessionManager($this->_getSessionManagerStub(false, true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchNoSessionForAuthorizationCode ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub(Response\Token::ERROR_INVALID_GRANT_NO_SESSION));
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        $this->_dispatcher->setSessionManager($this->_getSessionManagerStub());
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchOk ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub());
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        
        $this->_dispatcher->setSessionManager($this->_getSessionManagerStub(false, false, true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function _testDispatch ()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function _getRequestStub ($invalid = false)
    {
        $request = $this->getMockBuilder('\PhpIdServer\OpenIdConnect\Request\Token')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(! $invalid));
        $request->expects($this->any())
            ->method('getInvalidReasons')
            ->will($this->returnValue(array(
            'test_reason'
        )));
        
        return $request;
    }


    protected function _getResponseStub ($expectError = NULL)
    {
        $response = $this->getMockBuilder('\PhpIdServer\OpenIdConnect\Response\Token')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        
        if ($expectError !== NULL) {
            $response->expects($this->once())
                ->method('setError')
                ->with($this->stringContains($expectError));
        } else {
            $response->expects($this->never())
                ->method('setError');
            
            $response->expects($this->once())
                ->method('setTokenEntity');
        }
        
        return $response;
    }


    protected function _getClientRegistryStub ($noClient = false)
    {
        $registry = $this->getMockBuilder('\PhpIdServer\Client\Registry\Registry')
            ->disableOriginalConstructor()
            ->getMock();
        
        if (! $noClient) {
            $client = $this->_getClientStub();
            $registry->expects($this->any())
                ->method('getClientById')
                ->will($this->returnValue($client));
        }
        
        return $registry;
    }


    protected function _getSessionManagerStub ($noAuthorizationCode = false, $expired = false, $returnSession = false)
    {
        $sm = $this->getMockBuilder('\PhpIdServer\Session\SessionManager')
            ->getMock();
        
        $sm->expects($this->any())
            ->method('createAccessToken')
            ->will($this->returnValue($this->_getAccessTokenStub()));
        
        if (! $noAuthorizationCode) {
            $authorizationCode = $this->_getAuthorizationCodeStub($expired);
            
            $sm->expects($this->any())
                ->method('getAuthorizationCode')
                ->will($this->returnValue($authorizationCode));
        }
        
        if ($returnSession) {
            $sm->expects($this->any())
                ->method('getSessionForAuthorizationCode')
                ->will($this->returnValue($this->_getSessionStub()));
        }
        
        return $sm;
    }


    protected function _getClientStub ()
    {
        $client = $this->getMock('\PhpIdServer\Client\Client');
        
        return $client;
    }


    protected function _getAuthorizationCodeStub ($expired = false)
    {
        $authorizationCode = $this->getMock('\PhpIdServer\Session\Token\AuthorizationCode');
        $authorizationCode->expects($this->any())
            ->method('isExpired')
            ->will($this->returnValue($expired));
        
        return $authorizationCode;
    }


    protected function _getSessionStub ()
    {
        $session = $this->getMock('\PhpIdServer\Session\Session');
        
        return $session;
    }


    protected function _getAccessTokenStub ()
    {
        $accessToken = $this->getMock('\PhpIdServer\Session\Token\AccessToken');
        
        return $accessToken;
    }
}