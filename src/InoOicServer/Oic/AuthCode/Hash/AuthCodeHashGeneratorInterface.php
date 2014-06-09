<?php

namespace InoOicServer\Oic\AuthCode\Hash;

use InoOicServer\Crypto\Hash\GenericHashGeneratorInterface;
use InoOicServer\Oic\Session\Session;


interface AuthCodeHashGeneratorInterface extends GenericHashGeneratorInterface
{


    /**
     * @param Session $session
     * @param string $salt
     * @param string $algo
     * @return string
     */
    public function generateAuthCodeHash(Session $session, $salt, $algo = null);
}