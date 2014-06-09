<?php

namespace InoOicServer\Crypto\Hash;

use Zend\Crypt\Hash;


class PhpHash implements GenericHashGeneratorInterface
{


    public function generate()
    {}


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Crypto\Hash\HashGeneratorInterface::generateHash()
     */
    public function generateHash($algo, $data, $salt = null)
    {
        return Hash::compute($algo, $data . $salt);
    }
}