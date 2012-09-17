<?php

namespace PhpIdServer\OpenIdConnect\Request\Authorize;

use PhpIdServer\OpenIdConnect\Request\AbstractRequest;

/**
 * Simple "authorize" request method as described in the specs:
 * 
 * http://openid.net/specs/openid-connect-standard-1_0.html#anchor3
 *
 */
class Simple extends AbstractRequest
{


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
}