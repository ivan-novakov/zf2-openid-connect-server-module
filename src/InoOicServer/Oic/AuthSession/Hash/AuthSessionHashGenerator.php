<?php

namespace InoOicServer\Oic\AuthSession\Hash;

use InoOicServer\Oic\User\Authentication\Status;
use InoOicServer\Crypto\Hash\PhpHash;


class AuthSessionHashGenerator extends PhpHash implements AuthSessionHashGeneratorInterface
{


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGeneratorInterface::generateAuthSessionHash()
     */
    public function generateAuthSessionHash(Status $authStatus, $salt, $algo = null)
    {
        $data = $authStatus->getIdentity()->getId() . $authStatus->getTime()->getTimestamp();
        
        return $this->generateHash($data, $salt, $algo);
    }
}