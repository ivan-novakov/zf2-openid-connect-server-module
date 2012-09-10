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


    public function getClientId ()
    {
        return $this->_getParam(Field::CLIENT_ID);
    }


    public function getResponseType ()
    {
        return $this->_getParam(Field::RESPONSE_TYPE);
    }


    public function getRedirectUri ()
    {
        return $this->_getParam(Field::REDIRECT_URI);
    }


    public function isPostRequest ()
    {
        return (\Zend\Http\Request::METHOD_POST == $this->_httpRequest->getMethod());
    }


    protected function _getParam ($name)
    {
        if ($this->isPostRequest()) {
            return $this->_httpRequest->getPost($name);
        }
        
        return $this->_httpRequest->getQuery($name);
    }
}