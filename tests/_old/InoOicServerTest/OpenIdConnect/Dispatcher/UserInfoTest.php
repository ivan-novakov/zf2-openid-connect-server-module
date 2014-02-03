<?php

namespace InoOicServerTest\OpenIdConnect\Dispatcher;

use InoOicServer\OpenIdConnect\Dispatcher;
use InoOicServer\OpenIdConnect\Request;
use InoOicServer\OpenIdConnect\Response;
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
        
        $this->assertInstanceOf('\InoOicServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchMissingSessionManagerDependencyException ()
    {
        $this->setExpectedException('InoOicServer\General\Exception\MissingDependencyException');
        
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
        
        $this->assertInstanceOf('\InoOicServer\OpenIdConnect\Response\UserInfo', $response);
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
        
        $this->assertInstanceOf('\InoOicServer\OpenIdConnect\Response\UserInfo', $response);
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
        
        $this->assertInstanceOf('\InoOicServer\OpenIdConnect\Response\UserInfo', $response);
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
        
        $this->assertInstanceOf('\InoOicServer\OpenIdConnect\Response\UserInfo', $response);
    }


    public function testDispatchOk ()
    {
        $user = $this->_getUserMock();
        
        $smStub = $this->_getSessionManagerStub();
        $this->_expectSessionManagerReturnToken($smStub);
        $this->_expectSessionManagerReturnSession($smStub);
        $this->_expectSessionManagerReturnUser($smStub, $user);
        
        $this->_dispatcher->setSessionManager($smStub);
        $this->_dispatcher->setUserInfoRequest($this->_getRequestStub());
        
        $response = $this->_getResponseStub();
        $this->_expectResponseOk($response, 'setUserData');
        $this->_dispatcher->setUserInfoResponse($response);
        
        $userInfoMapper = $this->getMock('InoOicServer\User\UserInfo\Mapper\MapperInterface');
        $userInfoMapper->expects($this->once())
            ->method('getUserInfoData')
            ->with($user)
            ->will($this->returnValue(array()));
        $this->_dispatcher->setUserInfoMapper($userInfoMapper);
        
        $response = $this->_dispatcher->dispatch();
        
        $this->assertInstanceOf('\InoOicServer\OpenIdConnect\Response\UserInfo', $response);
    }


    protected function _getSessionManagerStub ()
    {
        $sm = $this->getMockBuilder('\InoOicServer\Session\SessionManager')
            ->getMock();
        
        return $sm;
    }


    public function _getRequestStub ($invalid = false)
    {
        $request = $this->getMockBuilder('\InoOicServer\OpenIdConnect\Request\UserInfo')
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
        $response = $this->getMockBuilder('\InoOicServer\OpenIdConnect\Response\UserInfo')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        
        return $response;
    }


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


    protected function _expectSessionManagerReturnUser ($sm, $user = null)
    {
        if (null === $user) {
            $user = $this->_getUserMock();
        }
        
        $sm->expects($this->once())
            ->method('getUserFromSession')
            ->will($this->returnValue($user));
    }


    protected function _getUserMock ()
    {
        return $this->getMock('\InoOicServer\User\UserInterface');
    }
}