<?php

namespace InoOicServer\Util\TokenGenerator;


interface TokenGeneratorInterface
{


    public function generate(array $inputValues = array());
}
