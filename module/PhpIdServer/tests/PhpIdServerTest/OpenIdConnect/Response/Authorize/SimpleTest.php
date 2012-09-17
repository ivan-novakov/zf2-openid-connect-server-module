<?php

namespace PhpIdServerTest\OpenIdConnect\Response\Authorize;

use PhpIdServer\OpenIdConnect\Response\Authorize;


class SimpleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Authorize/Simple response object.
     * 
     * @var Authorize\Simple
     */
    protected $_response = NULL;


    public function setUp ()
    {
        $httpResponse = new \Zend\Http\Response();
        $this->_response = new Authorize\Simple($httpResponse);
    }


    public function testSetRedirectLocation ()
    {
        $this->_response->setRedirectLocation('http://redirect');
        $this->_response->setAuthorizationCode('code_123');
        $this->assertEquals('http://redirect/?code=code_123', $this->_response->getHttpResponse()
            ->getHeaders()
            ->get('Location')
            ->getFieldValue());
    }
    
    
}