<?php

namespace PhpIdServer\OpenIdConnect\Request;


abstract class AbstractRequest
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


    /**
     * Returns true, if the request is a POST request.
     * 
     * @return boolean
     */
    public function isPostRequest ()
    {
        return (\Zend\Http\Request::METHOD_POST == $this->_httpRequest->getMethod());
    }


    /**
     * Returns HTTP GET or POST argument value based on the current request method.
     * 
     * @param string $name
     * @return mixed
     */
    protected function _getParam ($name)
    {
        if ($this->isPostRequest()) {
            return $this->_httpRequest->getPost($name);
        }
        
        return $this->_httpRequest->getQuery($name);
    }
}