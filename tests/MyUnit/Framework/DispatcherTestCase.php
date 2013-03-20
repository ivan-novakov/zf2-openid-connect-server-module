<?php

namespace MyUnit\Framework;


class DispatcherTestCase extends \PHPUnit_Framework_TestCase
{


    public function testDispatchMissingRequestDependencyException()
    {
        $this->setExpectedException('\PhpIdServer\General\Exception\MissingDependencyException');
        
        $this->_dispatcher->dispatch();
    }


    protected function _getSessionManagerStub($noAuthorizationCode = false, $expired = false, $returnSession = false)
    {
        $sm = $this->getMockBuilder('\PhpIdServer\Session\SessionManager')
            ->getMock();
        
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


    protected function _getAuthorizationCodeStub($expired = false)
    {
        $authorizationCode = $this->getMock('\PhpIdServer\Session\Token\AuthorizationCode');
        $authorizationCode->expects($this->any())
            ->method('isExpired')
            ->will($this->returnValue($expired));
        
        return $authorizationCode;
    }


    protected function _getAccessTokenStub($expired = false)
    {
        $accessToken = $this->getMock('\PhpIdServer\Session\Token\AccessToken');
        $accessToken->expects($this->any())
            ->method('isExpired')
            ->will($this->returnValue($expired));
        
        return $accessToken;
    }


    protected function _getClientStub()
    {
        $client = $this->getMock('\PhpIdServer\Client\Client');
        $client->expects($this->any())
            ->method('getAuthenticationInfo')
            ->will($this->returnValue($this->_getAuthenticationInfoStub()));
        
        return $client;
    }


    protected function _getAuthenticationInfoStub($method = 'dummy')
    {
        $authenticationInfo = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $authenticationInfo->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue($method));
        
        return $authenticationInfo;
    }


    protected function _getSessionStub()
    {
        $session = $this->getMock('\PhpIdServer\Session\Session');
        
        return $session;
    }


    protected function _expectResponseError($response, $expectError)
    {
        $response->expects($this->once())
            ->method('setError')
            ->with($this->stringContains($expectError));
    }


    protected function _expectResponseOk($response, $checkMethod = NULL)
    {
        $response->expects($this->never())
            ->method('setError');
        
        if (NULL !== $checkMethod) {
            $response->expects($this->once())
                ->method($checkMethod);
        }
    }
}