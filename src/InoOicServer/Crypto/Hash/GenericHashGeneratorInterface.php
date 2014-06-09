<?php

namespace InoOicServer\Crypto\Hash;


interface GenericHashGeneratorInterface
{


    /**
     * @param string $algo
     * @param string $data
     * @param string $salt
     * @return string
     */
    public function generateHash($algo, $data, $salt = null);
}
