<?php

namespace PhpIdServer\Session\Storage;

use PhpIdServer\Session\Token\RefreshToken;
use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Session\Session;


class Dummy extends AbstractStorage
{

    protected $_sessions = array();

    protected $_authorizationCodes = array();

    protected $_accessTokens = array();


    public function loadSession ($sessionId)
    {
        if (isset($this->_sessions[$sessionId])) {
            return $this->_sessions[$sessionId];
        }
        
        return NULL;
    }


    public function saveSession (Session $session)
    {
        $this->_sessions[$session->getId()] = $session;
    }


    public function loadAuthorizationCode ($code)
    {
        if (isset($this->_authorizationCodes[$code])) {
            return $this->_authorizationCodes[$code];
        }
        
        return NULL;
    }


    public function saveAuthorizationCode (AuthorizationCode $authorizationCode)
    {
        $this->_authorizationCodes[$authorizationCode->getId()] = $authorizationCode;
    }


    public function deleteAuthorizationCode (AuthorizationCode $authorizationCode)
    {}


    public function loadAccessToken ($token)
    {
        if (isset($this->_accessTokens[$token])) {
            return $this->_accessTokens[$token];
        }
        
        return NULL;
    }


    public function saveAccessToken (AccessToken $accessToken)
    {
        $this->_accessTokens[$accessToken->getToken()] = $accessToken;
    }


    public function loadRefreshToken ($code)
    {}


    public function saveRefreshToken (RefreshToken $refreshToken)
    {}
}