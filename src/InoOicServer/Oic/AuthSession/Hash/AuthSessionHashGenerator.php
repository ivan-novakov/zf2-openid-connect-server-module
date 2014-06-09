<?php

namespace InoOicServer\Oic\AuthSession\Hash;

use InoOicServer\Oic\User\Authentication\Status;
use InoOicServer\Crypto\Hash\PhpHash;


class AuthSessionHashGenerator extends PhpHash implements AuthSessionHashGeneratorInterface
{

    /**
     * @var string
     */
    protected $defaultAlgo = 'sha1';


    /**
     * @return string
     */
    public function getDefaultAlgo()
    {
        return $this->defaultAlgo;
    }


    /**
     * @param string $defaultAlgo
     */
    public function setDefaultAlgo($defaultAlgo)
    {
        $this->defaultAlgo = $defaultAlgo;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\AuthSession\Hash\AuthSessionHashGeneratorInterface::generateAuthSessionHash()
     */
    public function generateAuthSessionHash(Status $authStatus, $salt, $algo = null)
    {
        if (null === $algo) {
            $algo = $this->defaultAlgo;
        }
        
        $data = $authStatus->getIdentity()->getId() . $authStatus->getTime()->getTimestamp();
        
        return $this->generateHash($algo, $data, $salt);
    }
}