<?php

namespace InoOicServer\Oic\Session\Hash;

use InoOicServer\Crypto\Hash\GenericHashGeneratorInterface;
use InoOicServer\Oic\AuthSession\AuthSession;


interface SessionHashGeneratorInterface extends GenericHashGeneratorInterface
{


    /**
     * @param AuthSession $authSession
     * @param string $salt
     * @param string $algo
     * @return string
     */
    public function generateSessionHash(AuthSession $authSession, $salt, $algo = null);
}