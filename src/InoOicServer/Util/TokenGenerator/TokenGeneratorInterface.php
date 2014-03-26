<?php

namespace InoOicServer\Util\TokenGenerator;


interface TokenGeneratorInterface
{


    /**
     * Generates a token based on the input values.
     * 
     * @param array $inputValues
     * @return string
     */
    public function generate(array $inputValues = array());
}
