<?php

namespace InoOicServerTest\Client\Authentication\Method;

use InoOicServer\Client\Authentication\Method\SecretBasic;
use Zend\Http;


class SecretBasicTest extends \PHPUnit_Framework_Testcase
{

    /**
     * @var SecretBasic
     */
    protected $method;

    protected $clientId = 'abc';

    protected $clientSecret = '123';


    public function setUp()
    {
        $this->method = new SecretBasic();
    }


    public function testAuthenticateWithMissingHeader()
    {
        $info = $this->createAuthenticationInfoMock();
        $httpRequest = $this->createHttpRequestMock();
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('Missing authorization header', $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateWithUnsupportedAuth()
    {
        $info = $this->createAuthenticationInfoMock();
        $httpRequest = $this->createHttpRequestMock('foo bar');
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame("Unsupported authorization 'foo'", $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateWithMissingHash()
    {
        $info = $this->createAuthenticationInfoMock();
        $httpRequest = $this->createHttpRequestMock('basic');
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('Missing authorization hash', $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateWithInvalidHash()
    {
        $info = $this->createAuthenticationInfoMock($this->clientId, $this->clientSecret);
        $httpRequest = $this->createHttpRequestMock('basic some_invalid_hash');
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('Invalid authorization', $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateOk()
    {
        $info = $this->createAuthenticationInfoMock($this->clientId, $this->clientSecret);
        
        $hash = base64_encode(sprintf("%s:%s", $this->clientId, $this->clientSecret));
        
        $httpRequest = $this->createHttpRequestMock('basic ' . $hash);
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertTrue($result->isAuthenticated());
    }
    
    /*
     * --------------------------
     */
    protected function createHttpRequestMock($authHeaderValue = null)
    {
        if ($authHeaderValue) {
            $header = $this->getMock('Zend\Http\Header\Authorization');
            $header->expects($this->once())
                ->method('getFieldValue')
                ->will($this->returnValue($authHeaderValue));
            $authHeaderValue = $header;
        }
        
        $httpRequest = $this->getMock('Zend\Http\Request');
        $httpRequest->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->will($this->returnValue($authHeaderValue));
        
        return $httpRequest;
    }


    protected function createAuthenticationInfoMock($clientId = null, $clientSecret = null)
    {
        $info = $this->getMockBuilder('InoOicServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();

        if ($clientId && $clientSecret) {
            $info->expects($this->once())
                ->method('getClientId')
                ->will($this->returnValue($clientId));
            $info->expects($this->once())
                ->method('getOption')
                ->with('secret')
                ->will($this->returnValue($clientSecret));
        }
        
        return $info;
    }
}