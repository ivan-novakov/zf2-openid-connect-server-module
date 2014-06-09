<?php

namespace InoOicServer\Oic\AuthSession\Hash;

use InoOicServer\Oic\User;
use InoOicServer\Crypto\Hash\GenericHashGeneratorInterface;


interface AuthSessionHashGeneratorInterface extends GenericHashGeneratorInterface
{


    public function generateAuthSessionHash(User\Authentication\Status $authStatus, $salt, $algo = null);
}