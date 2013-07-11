<?php

namespace InoOicServer\OpenIdConnect\Request;

use InoOicServer\Http\AuthorizationHeaderParser;
use InoOicServer\Client;


/**
 * Token request object.
 *
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Token extends AbstractRequest
{

    const FIELD_CODE = 'code';

    const FIELD_GRANT_TYPE = 'grant_type';

    const FIELD_REDIRECT_URI = 'redirect_uri';

    const FIELD_CLIENT_ID = 'client_id';


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