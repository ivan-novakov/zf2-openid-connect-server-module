<?php

namespace InoOicServer\Oic\Session;

use InoOicServer\Oic\AuthSession\AuthSession;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\AccessToken\AccessToken;
use InoOicServer\Oic\User\UserInterface;


interface SessionServiceInterface
{


    public function createSession(AuthSession $authSession, $nonce = null);


    public function fetchSession($id);


    public function saveSession(Session $session);


    public function fetchSessionByCode(AuthCode $authCode);


    public function fetchSessionByAccessToken(AccessToken $accessToken);


    public function fetchSessionByUser(UserInterface $user);


    public function fetchSessionByAuthSession(AuthSession $authSession);


    /**
     * Creates a new session based on the auth session or re-uses an existing valid session.
     * 
     * @param AuthSession $authSession
     * @param string $nonce
     * @return Session
     */
    public function initSessionFromAuthSession(AuthSession $authSession, $nonce = null);
}