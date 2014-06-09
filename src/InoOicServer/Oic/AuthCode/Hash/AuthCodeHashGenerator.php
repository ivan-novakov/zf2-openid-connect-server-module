<?php

namespace InoOicServer\Oic\AuthCode\Hash;

use InoOicServer\Crypto\Hash\PhpHash;
use InoOicServer\Oic\Session\Session;


class AuthCodeHashGenerator extends PhpHash implements AuthCodeHashGeneratorInterface
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
     * @see \InoOicServer\Oic\AuthCode\Hash\AuthCodeHashGeneratorInterface::generateAuthCodeHash()
     */
    public function generateAuthCodeHash(Session $session, $salt, $algo = null)
    {
        if (null === $algo) {
            $algo = $this->defaultAlgo;
        }
        
        $data = $session->getId() . $session->getCreateTime()->getTimestamp();
        
        return $this->generateHash($algo, $data, $salt);
    }
}