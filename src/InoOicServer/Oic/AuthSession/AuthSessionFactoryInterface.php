<?php

namespace InoOicServer\Oic\AuthSession;

use InoOicServer\Oic\User;


interface AuthSessionFactoryInterface
{


    /**
     * @param User\Authentication\Status $authStatus
     * @param integer $age
     * @param string $salt
     * @return AuthSession
     */
    public function createAuthSession(User\Authentication\Status $authStatus, $age, $salt);
}