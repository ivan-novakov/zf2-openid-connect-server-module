<?php

namespace PhpIdServer\Session\IdGenerator;


interface IdGeneratorInterface
{


    /**
     * Generates a unique session ID. Optionally the user and client info may be used.
     * 
     * @param array $inpuTvalues
     * @return string
     */
    public function generateId (Array $inputValues = array());
}