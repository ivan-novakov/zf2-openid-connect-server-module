<?php

namespace InoOicServer\OpenIdConnect\Request;


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
    protected $authorizationType = NULL;

    /**
     * The authorization value part of the Authorization header.
     * 
     * @var string
     */
    protected $authorizationValue = NULL;


    /**
     * Returns the authorization type of the request.
     * 
     * @return string|NULL
     */
    public function getAuthorizationType()
    {
        if (NULL === $this->authorizationType) {
            $this->parseAuthorizationHeader();
        }
        
        return $this->authorizationType;
    }


    /**
     * Returns the authorization value of the request.
     * 
     * @return string|NULL
     */
    public function getAuthorizationValue()
    {
        if (NULL === $this->authorizationValue) {
            $this->parseAuthorizationHeader();
        }
        
        return $this->authorizationValue;
    }


    /**
     * Returns the value of the Authorization header.
     * 
     * @return string|NULL
     */
    public function getAuthorization()
    {
        $header = $this->httpRequest->getHeader('Authorization');
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
    protected function parseAuthorizationHeader()
    {
        $authorization = trim($this->getAuthorization());
        if ($authorization) {
            $parts = explode(' ', $authorization);
            if (count($parts) != 2) {
                throw new Exception\InvalidAuthorizationException(
                    sprintf("Unexpected number or authorization parts: %d", count($parts)));
            }
            
            $this->authorizationType = strtolower(trim($parts[0]));
            $this->authorizationValue = trim($parts[1]);
        }
    }


    /**
     * (non-PHPdoc)
     * @see \InoOicServer\OpenIdConnect\Request\AbstractRequest::_validate()
     */
    protected function validate()
    {
        $reasons = array();
        
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