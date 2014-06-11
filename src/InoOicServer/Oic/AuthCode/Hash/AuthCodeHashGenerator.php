<?php

namespace InoOicServer\Oic\AuthCode\Hash;

use InoOicServer\Crypto\Hash\PhpHash;
use InoOicServer\Oic\Session\Session;


class AuthCodeHashGenerator extends PhpHash implements AuthCodeHashGeneratorInterface
{


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthCode\Hash\AuthCodeHashGeneratorInterface::generateAuthCodeHash()
     */
    public function generateAuthCodeHash(Session $session, $salt, $algo = null)
    {
        $data = $session->getId() . $session->getCreateTime()->getTimestamp();
        
        return $this->generateHash($data, $salt, $algo);
    }
}