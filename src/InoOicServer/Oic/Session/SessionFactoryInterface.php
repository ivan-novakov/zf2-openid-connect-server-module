<?php

namespace InoOicServer\Oic\Session;

use InoOicServer\Oic\AuthSession\AuthSession;


interface SessionFactoryInterface
{


    /**
     * @param AuthSession $authSession
     * @param integer $age
     * @param string $salt
     * @param string $nonce
     * @return Session
     */
    public function createSession(AuthSession $authSession, $age, $salt, $nonce = null);
}
