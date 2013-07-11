<?php

namespace InoOicServerTest\OpenIdConnect\Request;

use InoOicServer\OpenIdConnect\Request\Token;
use InoOicServer\OpenIdConnect\Request;


class TokenTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Reuqest object.
     * 
     * @var Request\Token
     */
    protected $request = NULL;


    public function setUp()
    {
        $httpRequest = new \Zend\Http\Request();
        $httpRequest->getPost()->fromArray(
            array(
                Request\Token::FIELD_CODE => 'authorization_code_123',
                Request\Token::FIELD_CLIENT_ID => 'testclient',
                Request\Token::FIELD_GRANT_TYPE => 'authorization_code',
                Request\Token::FIELD_REDIRECT_URI => 'http://dummy'
            ));
        
        $this->request = new Request\Token($httpRequest);
    }


    public function testGetClientId()
    {
        $this->assertEquals('testclient', $this->request->getClientId());
    }


    public function testGetCode()
    {
        $this->assertEquals('authorization_code_123', $this->request->getCode());
    }


    public function testGetGrantType()
    {
        $this->assertEquals('authorization_code', $this->request->getGrantType());
    }


    public function testGetRedirectUri()
    {
        $this->assertEquals('http://dummy', $this->request->getRedirectUri());
    }


    public function testValidate()
    {
        $request = new Request\Token(new \Zend\Http\Request());
        $reasons = $request->getInvalidReasons();
        
        $this->assertCount(4, $reasons);
    }


    protected function _setAuthorizationHeader(Token $request, $value)
    {
        $request->getHttpRequest()
            ->getHeaders()
            ->addHeaders(array(
            'Authorization' => $value
        ));
    }
}