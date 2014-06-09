<?php

namespace InoOicServer\Oic\Session\Hash;

use InoOicServer\Crypto\Hash\PhpHash;
use InoOicServer\Oic\AuthSession\AuthSession;


class SessionHashGenerator extends PhpHash implements SessionHashGeneratorInterface
{

    /**
     * @var string
     */
    protected $defaultAlgo;


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
     * @see \InoOicServer\Oic\Session\Hash\SessionHashGeneratorInterface::generateSessionHash()
     */
    public function generateSessionHash(AuthSession $authSession, $salt, $algo = null)
    {
        if (null === $algo) {
            $algo = $this->defaultAlgo;
        }
        
        $data = $authSession->getId() . $authSession->getCreateTime()->getTimestamp();
        
        return $this->generateHash($algo, $data, $salt);
    }
}