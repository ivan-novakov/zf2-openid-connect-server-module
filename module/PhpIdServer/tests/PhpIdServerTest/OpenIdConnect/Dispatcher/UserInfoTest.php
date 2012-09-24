<?php

namespace PhpIdServerTest\OpenIdConnect\Dispatcher;

use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;
use MyUnit\Framework\DispatcherTestCase;


class UserInfoTest extends DispatcherTestCase
{

    /**
     * The dispatcher object.
     *
     * @var Dispatcher\UserInfo
     */
    protected $_dispatcher = NULL;


    public function setUp ()
    {
        $this->_dispatcher = new Dispatcher\UserInfo();
    }


    public function testDispatchRequestInvalid ()
    {
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub(true));
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\UserInfo::ERROR_INVALID_REQUEST);
        $this->_dispatcher->setUserInfoResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchMissingSessionManagerDependencyException ()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\MissingDependencyException');
        
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        $this->_dispatcher->setUserInfoResponse($this->_getResponseStub());
        
        $response = $this->_dispatcher->dispatch();
    }


    public function testDispatchTokenNotFound ()
    {
        $this->_dispatcher->setSessionManager($this->_getSessionManagerStub());
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\UserInfo::ERROR_INVALID_TOKEN_NOT_FOUND);
        $this->_dispatcher->setUserInfoResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchTokenExpired ()
    {
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerReturnToken($smStub, true);
        
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\UserInfo::ERROR_INVALID_TOKEN_EXPIRED);
        $this->_dispatcher->setUserInfoResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchNoSessionAssociated ()
    {
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerReturnToken($smStub);
        
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\UserInfo::ERROR_INVALID_TOKEN_NO_SESSION);
        $this->_dispatcher->setUserInfoResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchNoUserData ()
    {
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerReturnToken($smStub);
        $this->_expectSessionManagerReturnSession($smStub);
        
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseError($response, Response\UserInfo::ERROR_INVALID_TOKEN_NO_USER_DATA);
        $this->_dispatcher->setUserInfoResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchOk ()
    {
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerReturnToken($smStub);
        $this->_expectSessionManagerReturnSession($smStub);
        $this->_expectSessionManagerReturnUser($smStub);
        
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseOk($response, 'setUserEntity');
        $this->_dispatcher->setUserInfoResponse($response);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\PhpIdServer\OpenIdConnect\Response\UserInfo', $response);
    }


    protected function _getSessionManagerStub ()
    {
        $sm = $this->getMockBuilder('\PhpIdServer\Session\SessionManager')
            ->getMock();
        
        return $sm;
    }


    public function _getRequestStub ($invalid = false)
    {
        $request = $this->getMockBuilder('\PhpIdServer\OpenIdConnect\Request\UserInfo')
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
        $response = $this->getMockBuilder('\PhpIdServer\OpenIdConnect\Response\UserInfo')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        
        return $response;
    }
    
    /*
    protected function _expectResponseError ($response, $expectError)
    {
        $response->expects($this->once())
            ->method('setError')
            ->with($this->stringContains($expectError));
    }


    protected function _expectResponseOk ($response)
    {
        $response->expects($this->never())
            ->method('setError');
        
        $response->expects($this->once())
            ->method('setUserEntity');
    }
*/
    protected function _expectSessionManagerReturnToken ($sm, $expired = false)
    {
        $accessToken = $this->_getAccessTokenStub($expired);
        $sm->expects($this->any())
            ->method('getAccessToken')
            ->will($this->returnValue($accessToken));
    }


    protected function _expectSessionManagerReturnSession ($sm)
    {
        $sm->expects($this->once())
            ->method('getSessionByAccessToken')
            ->will($this->returnValue($this->_getSessionStub()));
    }


    protected function _expectSessionManagerReturnUser ($sm)
    {
        $sm->expects($this->once())
            ->method('getUserFromSession')
            ->will($this->returnValue($this->getMock('\PhpIdServer\User\User')));
    }
}