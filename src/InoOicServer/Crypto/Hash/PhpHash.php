<?php

namespace InoOicServer\Crypto\Hash;

use Zend\Crypt\Hash;


class PhpHash implements GenericHashGeneratorInterface
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
     * @see \InoOicServer\Crypto\Hash\HashGeneratorInterface::generateHash()
     */
    public function generateHash($data, $salt = null, $algo = null)
    {
        if (null === $algo) {
            $algo = $this->getDefaultAlgo();
        }
        
        return Hash::compute($algo, $data . $salt);
    }
}