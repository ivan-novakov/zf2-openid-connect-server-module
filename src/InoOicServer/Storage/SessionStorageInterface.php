<?php

namespace InoOicServer\Storage;

use InoOicServer\Session\Session;


interface SessionStorageInterface
{


    /**
     * Saves the session to the storage.
     * 
     * @param Session $session
     */
    public function saveSession(Session $session);


    /**
     * Loads the session by its ID.
     * 
     * @param string $sessionId
     * @return Session
     */
    public function loadSession($sessionId);
}