<?php

namespace InoOicServerTest\Client\Authentication\Method;

use InoOicServer\Client\Authentication\Method\SecretPost;


class SecretPostTest extends \PHPUnit_Framework_Testcase
{

    protected $method;


    public function setUp()
    {
        $this->method = new SecretPost();
    }


    public function testGetClientIdFieldNameWithDefaultValue()
    {
        $this->assertSame('client_id', $this->method->getClientIdFieldName());
    }


    public function testGetClientIdFieldNameFromOptions()
    {
        $options = array(
            SecretPost::OPT_CLIENT_ID_FIELD => 'custom_client_id'
        );
        $this->method->setOptions($options);
        $this->assertSame('custom_client_id', $this->method->getClientIdFieldName());
    }


    public function testGetClientSecretFieldNameWithDefaultValue()
    {
        $this->assertSame('client_secret', $this->method->getClientSecretFieldName());
    }


    public function testGetClientSecretFieldFromOptions()
    {
        $options = array(
            SecretPost::OPT_CLIENT_SECRET_FIELD => 'custom_client_secret'
        );
        $this->method->setOptions($options);
        $this->assertSame('custom_client_secret', $this->method->getClientSecretFieldName());
    }


    public function testAuthenticateWithMissingClientId()
    {
        $info = $this->createAuthenticationInfoMock();
        $httpRequest = $this->createHttpRequestMock();
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('Missing client ID', $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateWithMissingClientSecret()
    {
        $clientId = 'abc';
        
        $info = $this->createAuthenticationInfoMock();
        $httpRequest = $this->createHttpRequestMock($clientId);
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('Missing client secret', $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateWithUnknownClient()
    {
        $clientId = 'abc';
        $clientSecret = '123';
        $storedClientId = 'def';
        
        $info = $this->createAuthenticationInfoMock($storedClientId);
        $httpRequest = $this->createHttpRequestMock($clientId, $clientSecret);
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame("Unknown client ID '$clientId'", $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateWithInvalidSecret()
    {
        $clientId = 'abc';
        $clientSecret = '123';
        $storedClientId = 'abc';
        $storedClientSecret = '456';
        
        $info = $this->createAuthenticationInfoMock($storedClientId, $storedClientSecret);
        $httpRequest = $this->createHttpRequestMock($clientId, $clientSecret);
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('Invalid authorization', $result->getNotAuthenticatedReason());
    }


    public function testAuthenticateOk()
    {
        $clientId = 'abc';
        $clientSecret = '123';
        $storedClientId = 'abc';
        $storedClientSecret = '123';
        
        $info = $this->createAuthenticationInfoMock($storedClientId, $storedClientSecret);
        $httpRequest = $this->createHttpRequestMock($clientId, $clientSecret);
        
        $result = $this->method->authenticate($info, $httpRequest);
        $this->assertTrue($result->isAuthenticated());
    }
    
    /*
     * ---------------------------
     */
    public function createHttpRequestMock($clientId = null, $clientSecret = null)
    {
        $postVars = $this->getMock('Zend\Stdlib\Parameters');
        if ($clientId) {
            $postVars->expects($this->at(0))
                ->method('get')
                ->with('client_id')
                ->will($this->returnValue($clientId));
            
            if ($clientSecret) {
                $postVars->expects($this->at(1))
                    ->method('get')
                    ->with('client_secret')
                    ->will($this->returnValue($clientSecret));
            }
        }
        
        $request = $this->getMock('Zend\Http\Request');
        $request->expects($this->once())
            ->method('getPost')
            ->will($this->returnValue($postVars));
        return $request;
    }


    public function createAuthenticationInfoMock($clientId = null, $clientSecret = null)
    {
        $info = $this->getMockBuilder('InoOicServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        
        if ($clientId) {
            $info->expects($this->once())
                ->method('getClientId')
                ->will($this->returnValue($clientId));
            
            if ($clientSecret) {
                $info->expects($this->once())
                    ->method('getOption')
                    ->with('secret')
                    ->will($this->returnValue($clientSecret));
            }
        }
        return $info;
    }
}