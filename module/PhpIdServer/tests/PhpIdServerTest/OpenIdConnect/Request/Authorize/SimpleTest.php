<?php

namespace PhpIdServerTest\OpenIdConnect\Request\Authorize;

use PhpIdServer\OpenIdConnect\Request\Authorize;


class SimpleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Authorize/Simple request.
     * 
     * @var Authorize\Simple
     */
    protected $_request = NULL;


    public function setUp ()
    {
        $httpRequest = new \Zend\Http\Request();
        $httpRequest->getQuery()
            ->fromArray(array(
            Authorize\Field::CLIENT_ID => 'client_id_123', 
            Authorize\Field::STATE => '123456', 
            Authorize\Field::RESPONSE_TYPE => 'code', 
            Authorize\Field::REDIRECT_URI => 'http://redirect', 
            Authorize\Field::NONCE => 'nonce_123'
        ));
        
        $this->_request = new Authorize\Simple($httpRequest);
    }


    public function testGetCLientId ()
    {
        $this->assertEquals('client_id_123', $this->_request->getClientId());
    }


    public function testGetState ()
    {
        $this->assertEquals('123456', $this->_request->getState());
    }


    public function testGetResponseType ()
    {
        $this->assertEquals('code', $this->_request->getResponseType());
    }


    public function testGetRedirectUri ()
    {
        $this->assertEquals('http://redirect', $this->_request->getRedirectUri());
    }


    public function testGetNonce ()
    {
        $this->assertEquals('nonce_123', $this->_request->getNonce());
    }
}