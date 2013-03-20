<?php

namespace PhpIdServerTest\OpenIdConnect\Dispatcher;

use MyUnit\Framework\DispatcherTestCase;
use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Client\Client;
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


    public function setUp()
    {
        $this->_dispatcher = new Dispatcher\Token();
    }


    public function testDispatchRequestInvalid()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub(true));
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\Token::ERROR_INVALID_REQUEST);
        $this->_dispatcher->setTokenResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchMissingClientRegistryDependencyException()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\MissingDependencyException');
        
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub());
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchInvalidClient()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\Token::ERROR_INVALID_CLIENT);
        $this->_dispatcher->setTokenResponse($response);
        
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchClientWithFailedAuthentication()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\Token::ERROR_INVALID_CLIENT);
        $this->_dispatcher->setTokenResponse($response);
        
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        $this->_dispatcher->setClientAuthenticationManager($this->_getClientAuthenticationManagerStub());
        
        $response = $this->_dispatcher->dispatch();
        $this->assertInstanceOf('PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchMissingSessionManagerDependencyException()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\MissingDependencyException');
        
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        $this->_dispatcher->setTokenResponse($this->_getResponseStub());
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        
        $response = $this->_dispatcher->dispatch();
    }


    public function testDispatchNoAuthorizationCode()
    {
        $request = $this->_getRequestStub();
        $this->_dispatcher->setTokenRequest($request);
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\Token::ERROR_INVALID_GRANT);
        $this->_dispatcher->setTokenResponse($response);
        
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        $this->_dispatcher->setSessionManager($this->_getSessionManagerStub(true));
        
        $this->_dispatcher->setClientAuthenticationManager($this->_getClientAuthenticationManagerStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchExpiredAuthorizationCode()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\Token::ERROR_INVALID_GRANT_EXPIRED);
        $this->_dispatcher->setTokenResponse($response);
        
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerGetAuthorizationCode($smStub, true);
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setClientAuthenticationManager($this->_getClientAuthenticationManagerStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchNoSessionForAuthorizationCode()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\Token::ERROR_INVALID_GRANT_NO_SESSION);
        $this->_dispatcher->setTokenResponse($response);
        
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerGetAuthorizationCode($smStub);
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setClientAuthenticationManager($this->_getClientAuthenticationManagerStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function testDispatchOk()
    {
        $this->_dispatcher->setTokenRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseOk($response, 'setTokenEntity');
        $this->_dispatcher->setTokenResponse($response);
        
        $this->_dispatcher->setClientRegistry($this->_getClientRegistryStub());
        
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerGetAuthorizationCode($smStub);
        $this->_expectSessionManagerReturnSession($smStub);
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setClientAuthenticationManager($this->_getClientAuthenticationManagerStub(true));
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\Token', $response);
    }


    public function _getRequestStub($invalid = false)
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


    protected function _getResponseStub($expectError = NULL)
    {
        $response = $this->getMockBuilder('\PhpIdServer\OpenIdConnect\Response\Token')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        
        return $response;
    }


    protected function _getClientRegistryStub($noClient = false)
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


    protected function _getSessionManagerStub($noAuthorizationCode = false, $expired = false, $returnSession = false)
    {
        $sm = $this->getMockBuilder('\PhpIdServer\Session\SessionManager')
            ->getMock();
        
        $sm->expects($this->any())
            ->method('createAccessToken')
            ->will($this->returnValue($this->_getAccessTokenStub()));
        
        return $sm;
    }


    protected function _getAccessTokenStub()
    {
        $accessToken = $this->getMock('\PhpIdServer\Session\Token\AccessToken');
        
        return $accessToken;
    }


    protected function _getClientAuthenticationManagerStub($authSuccess = false, $failureReason = 'auth failure reason')
    {
        $result = $this->getMock('PhpIdServer\Client\Authentication\Result');
        $result->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue($authSuccess));
        $result->expects($this->any())
            ->method('getReason')
            ->will($this->returnValue($failureReason));
        
        $clientAuthenticationManager = $this->getMock('PhpIdServer\Client\Authentication\Manager');
        $clientAuthenticationManager->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($result));
        
        return $clientAuthenticationManager;
    }


    protected function _expectSessionManagerGetAuthorizationCode($sm, $expired = false)
    {
        $authorizationCode = $this->_getAuthorizationCodeStub($expired);
        
        $sm->expects($this->any())
            ->method('getAuthorizationCode')
            ->will($this->returnValue($authorizationCode));
    }


    protected function _expectSessionManagerReturnSession($sm)
    {
        $sm->expects($this->once())
            ->method('getSessionForAuthorizationCode')
            ->will($this->returnValue($this->_getSessionStub()));
    }
}