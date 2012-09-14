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
     * @throws Exception\SaveException
     */
    public function saveSession (Session $session);


    /**
     * Loads an authorization code object from the storage.
     * 
     * @param string $code
     * @return AuthorizationCode
     */
    public function loadAuthorizationCode ($code);


    /**
     * Saves an authorization code object to the storage.
     * 
     * @param AuthorizationCode $authorizationCode
     */
    public function saveAuthorizationCode (AuthorizationCode $authorizationCode);


    public function deleteAuthorizationCode (AuthorizationCode $authorizationCode);


    public function loadAccessToken ($code);


    public function saveAccessToken (AccessToken $accessToken);


    public function loadRefreshToken ($code);


    public function saveRefreshToken (RefreshToken $refreshToken);
}