<?php

namespace PhpIdServer\OpenIdConnect\Response\Authorize;

use PhpIdServer\OpenIdConnect\Response\AbstractResponse;


class AbstractAuthorizeResponse extends AbstractResponse
{

    /**
     * A list of fields to be returned.
     * 
     * @var array
     */
    protected $_fields = array();

    /**
     * The redirect URI.
     * 
     * @var string
     */
    protected $_redirectLocation = NULL;


    public function getHttpResponse ()
    {
        $this->_httpResponse->getHeaders()
            ->addHeaders(array(
            'Location' => $this->_constructRedirectUri()
        ));
        
        $this->_httpResponse->setStatusCode(302);
        
        return parent::getHttpResponse();
    }


    /**
     * Sets the redirect URI.
     * 
     * @param string $location
     */
    public function setRedirectLocation ($location)
    {
        $this->_redirectLocation = $location;
    }


    protected function _constructRedirectUri ()
    {
        return $this->_redirectLocation;
    }


    protected function _addField ($fieldName, $fieldValue)
    {
        $this->_fields[$fieldName] = $fieldValue;
    }


    protected function _getFields ()
    {
        return $this->_fields;
    }


    protected function _isField ($fieldName)
    {
        return (isset($this->_fields[$fieldName]));
    }
}