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


    public function loadAccessToken ($code)
    {}


    public function saveAccessToken (AccessToken $accessToken)
    {}


    public function loadRefreshToken ($code)
    {}


    public function saveRefreshToken (RefreshToken $refreshToken)
    {}
}