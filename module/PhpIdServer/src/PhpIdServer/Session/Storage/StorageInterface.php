<?php

namespace PhpIdServer\Session\Storage;

use PhpIdServer\Session\Session;


interface StorageInterface
{


    public function loadSession ($sessionId);


    public function saveSession (Session $session);


    public function loadAuthorizationCode ($code);


    public function saveAuthorizationCode ();


    public function deleteAuthorizationCode ();


    public function loadAccessToken ($code);


    public function saveAccessToken ();


    public function loadRefreshToken ($code);


    public function saveRefreshToken ($code);
}