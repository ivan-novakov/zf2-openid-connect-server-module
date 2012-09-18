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

    protected $_invalidReasons = NULL;


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
     * Returns true, if the request is valid.
     * 
     * @return boolean
     */
    public function isValid ()
    {
        return (count($this->getInvalidReasons()) > 0);
    }


    public function getInvalidReasons ()
    {
        if (! is_array($this->_invalidReasons)) {
            $this->_invalidReasons = $this->_validate();
        }
        
        return $this->_invalidReasons;
    }


    protected function _validate ()
    {
        return array();
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
            return $this->_getPostParam($name);
        }
        
        return $this->_getGetParam($name);
    }


    /**
     * Returns the POST parameter with the provided name.
     * 
     * @param string $name
     * @return string
     */
    protected function _getPostParam ($name)
    {
        return $this->_httpRequest->getPost($name);
    }


    /**
     * Returns the GET parameter with the supplied name.
     * 
     * @param string $name
     * @return string
     */
    protected function _getGetParam ($name)
    {
        return $this->_httpRequest->getQuery($name);
    }
}