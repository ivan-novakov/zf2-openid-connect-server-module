<?php

namespace PhpIdServer\OpenIdConnect\Request;


/**
 * User info request.
 *
 */
class UserInfo extends AbstractRequest
{

    const FIELD_SCHEMA = 'schema';

    /**
     * The authorization type part of the Authorization header.
     * 
     * @var string
     */
    protected $_authorizationType = NULL;

    /**
     * The authorization value part of the Authorization header.
     * 
     * @var string
     */
    protected $_authorizationValue = NULL;


    /**
     * Returns the schema value.
     * 
     * @return string|NULL
     */
    public function getSchema()
    {
        return $this->_getGetParam(self::FIELD_SCHEMA);
    }


    /**
     * Returns the authorization type of the request.
     * 
     * @return string|NULL
     */
    public function getAuthorizationType()
    {
        if (NULL === $this->_authorizationType) {
            $this->_parseAuthorizationHeader();
        }
        
        return $this->_authorizationType;
    }


    /**
     * Returns the authorization value of the request.
     * 
     * @return string|NULL
     */
    public function getAuthorizationValue()
    {
        if (NULL === $this->_authorizationValue) {
            $this->_parseAuthorizationHeader();
        }
        
        return $this->_authorizationValue;
    }


    /**
     * Returns the value of the Authorization header.
     * 
     * @return string|NULL
     */
    public function getAuthorization()
    {
        $header = $this->_httpRequest->getHeader('Authorization');
        if ($header instanceof \Zend\Http\Header\Authorization) {
            return $header->getFieldValue();
        }
        
        return NULL;
    }


    /**
     * Parses the Authorization header and sets the corresponding object properties.
     * 
     * @throws Exception\InvalidAuthorizationException
     */
    protected function _parseAuthorizationHeader()
    {
        $authorization = trim($this->getAuthorization());
        if ($authorization) {
            $parts = explode(' ', $authorization);
            if (count($parts) != 2) {
                throw new Exception\InvalidAuthorizationException(
                    sprintf("Unexpected number or authorization parts: %d", count($parts)));
            }
            
            $this->_authorizationType = strtolower(trim($parts[0]));
            $this->_authorizationValue = trim($parts[1]);
        }
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\OpenIdConnect\Request\AbstractRequest::_validate()
     */
    protected function _validate()
    {
        $reasons = array();
        
        $schema = $this->getSchema();
        if ('openid' != $schema) {
            $reasons[] = sprintf("invalid schema '%s'", $schema);
        }
        
        $authorizationType = $this->getAuthorizationType();
        if ('bearer' != $authorizationType) {
            $reasons[] = sprintf("invalid auth type '%s'", $authorizationType);
        }
        
        if (NULL === $this->getAuthorizationValue()) {
            $reasons[] = 'auth value not set';
        }
        
        return $reasons;
    }
}