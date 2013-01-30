<?php

namespace PhpIdServer\OpenIdConnect\Request;


/**
 * Token request object.
 *
 */
class Token extends AbstractRequest implements ClientRequestInterface
{

    const FIELD_CODE = 'code';

    const FIELD_GRANT_TYPE = 'grant_type';

    const FIELD_REDIRECT_URI = 'redirect_uri';

    const FIELD_CLIENT_ID = 'client_id';


    public function getCode ()
    {
        return $this->_getPostParam(self::FIELD_CODE);
    }


    public function getGrantType ()
    {
        return $this->_getPostParam(self::FIELD_GRANT_TYPE);
    }


    public function getRedirectUri ()
    {
        return $this->_getPostParam(self::FIELD_REDIRECT_URI);
    }


    public function getClientId ()
    {
        return $this->_getPostParam(self::FIELD_CLIENT_ID);
    }


    protected function _validate ()
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