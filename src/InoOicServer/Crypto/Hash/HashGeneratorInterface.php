<?php

namespace InoOicServer\Crypto\Hash;


interface HashGeneratorInterface
{


    /**
     * Generates a token based on the input values.
     * 
     * @param array $inputValues
     * @return string
     */
    public function generate(array $inputValues = array());
}
