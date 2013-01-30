<?php

namespace PhpIdServer\OpenIdConnect\Request;

use PhpIdServer\Http\AuthorizationHeaderParser;
use PhpIdServer\Client;


/**
 * Token request object.
 *
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Token extends AbstractRequest implements ClientRequestInterface
{

    const FIELD_CODE = 'code';

    const FIELD_GRANT_TYPE = 'grant_type';

    const FIELD_REDIRECT_URI = 'redirect_uri';

    const FIELD_CLIENT_ID = 'client_id';

    /**
     * "Authorization" header parser.
     * 
     * @var AuthorizationHeaderParser
     */
    protected $_authorizationHeaderParser = null;


    /**
     * Sets the "Authorization" header parser.
     * 
     * @param AuthorizationHeaderParser $authorizationHeaderParser
     */
    public function setAuthorizationHeaderParser(AuthorizationHeaderParser $authorizationHeaderParser)
    {
        $this->_authorizationHeaderParser = $authorizationHeaderParser;
    }


    /**
     * Returns the "Authorization" header parser.
     * @return AuthorizationHeaderParser
     */
    public function getAuthorizationHeaderParser()
    {
        if (! ($this->_authorizationHeaderParser instanceof AuthorizationHeaderParser)) {
            $this->_authorizationHeaderParser = new AuthorizationHeaderParser();
        }
        
        return $this->_authorizationHeaderParser;
    }


    /**
     * Returns the "code" parameter value.
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->_getPostParam(self::FIELD_CODE);
    }


    /**
     * Returns the "grant_type" parameter value.
     * 
     * @return string
     */
    public function getGrantType()
    {
        return $this->_getPostParam(self::FIELD_GRANT_TYPE);
    }


    /**
     * Returns the "redirect_uri" parameter value.
     * 
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->_getPostParam(self::FIELD_REDIRECT_URI);
    }


    /**
     * Returns the "client_id" parameter value.
     * 
     * @return string
     */
    public function getClientId()
    {
        return $this->_getPostParam(self::FIELD_CLIENT_ID);
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\OpenIdConnect\Request\ClientRequestInterface::getAuthenticationData()
     */
    public function getAuthenticationData()
    {
        $authorizationHeader = $this->_getHeader('Authorization');
        if (! $authorizationHeader) {
            throw new Exception\InvalidClientAuthenticationException('Missing "Authorization" header');
        }
        
        $rawValue = $authorizationHeader->getFieldValue();
        
        $parser = $this->getAuthorizationHeaderParser();
        $data = $parser->parse($rawValue);
        
        if ($parser->isError()) {
            throw new Exception\InvalidClientAuthenticationException(
                sprintf("Error parsing the Authorization header: %s", implode(', ', $parser->getErrors())));
        }
        
        return $data;
    }


    protected function _validate()
    {
        $reasons = array();
        
        if (! $this->getCode()) {
            $reasons[] = 'missing [authorization_code]';
        }
        
        $grantType = $this->getGrantType();
        if (! $grantType || $grantType != 'authorization_code') {
            $reasons[] = 'missing or invalid [grant_type]';
        }
        
        if (! $this->getRedirectUri()) {
            $reasons[] = 'missing [redirect_uri]';
        }
        
        if (! $this->getClientId()) {
            $reasons[] = 'missing [client_id]';
        }
        
        return $reasons;
    }
}