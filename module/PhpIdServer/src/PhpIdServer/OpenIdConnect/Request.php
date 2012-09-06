<?php
namespace PhpIdServer\OpenIdConnect;


class Request
{

    /**
     * The HTTP request object.
     * 
     * @var \Zend\Http\Request
     */
    protected $_httpRequest = NULL;


    /**
     * Constructor.
     * 
     * @param \Zend\Http\Request $httpRequest
     */
    public function __construct (\Zend\Http\Request $httpRequest)
    {
        $this->_httpRequest = $httpRequest;
    }
}