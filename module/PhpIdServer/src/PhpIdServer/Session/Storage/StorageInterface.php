<?php

namespace PhpIdServer\Session\Storage;

use PhpIdServer\Session\Token\RefreshToken;
use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Session\Session;


interface StorageInterface
{


    /**
     * Loads a session from the storage.
     * 
     * @param string $sessionId
     * @return Session
     */
    public function loadSession ($sessionId);


    /**
     * Saves a session to the storage.
     * 
     * @param Session $session
     * @throws Exception\SaveSessionException
     */
    public function saveSession (Session $session);


    public function loadAuthorizationCode ($code);


    public function saveAuthorizationCode (AuthorizationCode $authorizationCode);


    public function deleteAuthorizationCode (AuthorizationCode $authorizationCode);


    public function loadAccessToken ($code);


    public function saveAccessToken (AccessToken $accessToken);


    public function loadRefreshToken ($code);


    public function saveRefreshToken (RefreshToken $refreshToken);
}