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
     * Returns the 'client_id' parameter value.
     * 
     * @return string
     */
    public function getClientId ()
    {
        return $this->_getParam(Field::CLIENT_ID);
    }


    /**
     * Returns the 'response_type' parameter value.
     * 
     * @return string
     */
    public function getResponseType ()
    {
        return $this->_getParam(Field::RESPONSE_TYPE);
    }


    /**
     * Returns the 'redirect_uri' parameter value.
     * 
     * @return string
     */
    public function getRedirectUri ()
    {
        return $this->_getParam(Field::REDIRECT_URI);
    }


    /**
     * Returns the 'state' parameter value.
     * 
     * @return string
     */
    public function getState ()
    {
        return $this->_getParam(Field::STATE);
    }


    /**
     * Returns the 'nonce' parameter value.
     * 
     * @return string
     */
    public function getNonce ()
    {
        return $this->_getParam(Field::NONCE);
    }


    /**
     * Returns the 'prompt' parameter value.
     * 
     * @return string
     */
    public function getPrompt ()
    {
        return $this->_getParam(Field::PROMPT);
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