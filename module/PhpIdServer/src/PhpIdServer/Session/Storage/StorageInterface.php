<?php

namespace PhpIdServer\Session\Storage;

use PhpIdServer\Session\Session;


interface StorageInterface
{


    /**
     * Looks for a session with the required ID, loads it and returns it.
     * 
     * @param string $sessionId
     * @return Session
     */
    public function loadSessionById ($sessionId);


    /**
     * Looks for a session with the required access token, loads it and returns it.
     * 
     * @param string $accessToken
     * @return Session
     */
    public function loadSessionByAccessToken ($accessToken);


    /**
     * Saves the session to the storage.
     * 
     * @param Session $session
     */
    public function saveSession (Session $session);


    /**
     * Deletes the session from storage.
     * 
     * @param Session $session
     */
    public function deleteSession (Session $session);
}