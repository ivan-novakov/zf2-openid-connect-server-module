<?php

namespace PhpIdServerTest\OpenIdConnect\Request;

use PhpIdServer\OpenIdConnect\Request;


class UserInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The user info request.
     * 
     * @var Request\UserInfo
     */
    protected $_request = NULL;


    public function setUp ()
    {
        $httpRequest = new \Zend\Http\Request('https://dummy/userinfo');
        $httpRequest->getQuery()
            ->fromArray(array(
            'schema' => 'openid'
        ));
        
        $httpRequest->getHeaders()
            ->addHeaders(array(
            'Authorization' => 'Bearer access_token_123'
        ));
        
        $this->_request = new Request\UserInfo($httpRequest);
    }


    public function testGetSchema ()
    {
        $this->assertEquals('openid', $this->_request->getSchema());
    }


    public function testGetAuthorizationType ()
    {
        $this->assertEquals('bearer', $this->_request->getAuthorizationType());
    }


    public function testGetAuthorizationValue ()
    {
        $this->assertEquals('access_token_123', $this->_request->getAuthorizationValue());
    }
}