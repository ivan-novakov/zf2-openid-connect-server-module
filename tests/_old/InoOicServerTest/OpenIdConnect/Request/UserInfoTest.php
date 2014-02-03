<?php

namespace InoOicServerTest\OpenIdConnect\Request;

use InoOicServer\OpenIdConnect\Request;


class UserInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The user info request.
     * 
     * @var Request\UserInfo
     */
    protected $request = NULL;


    public function setUp()
    {
        $httpRequest = new \Zend\Http\Request('https://dummy/userinfo');
        
        $httpRequest->getHeaders()->addHeaders(
            array(
                'Authorization' => 'Bearer access_token_123'
            ));
        
        $this->request = new Request\UserInfo($httpRequest);
    }


    public function testGetAuthorizationType()
    {
        $this->assertEquals('bearer', $this->request->getAuthorizationType());
    }


    public function testGetAuthorizationValue()
    {
        $this->assertEquals('access_token_123', $this->request->getAuthorizationValue());
    }
}