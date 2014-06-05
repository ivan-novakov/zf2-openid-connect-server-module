<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\User;


interface AuthSessionServiceInterface
{


    /**
     * @param User\Authentication\Status $authStatus
     * @return AuthSession
     */
    public function createSession(User\Authentication\Status $authStatus);


    /**
     * @param AuthSession $authSession
     */
    public function saveSession(AuthSession $authSession);


    /**
     * @param string $id
     * @return AuthSession|null
     */
    public function fetchSession($id);
}