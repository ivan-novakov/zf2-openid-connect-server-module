<?php

namespace PhpIdServer\Entity\Exception;


class InvalidMethodException extends \RuntimeException
{


    public function __construct ($method)
    {
        parent::__construct(sprintf("Invalid method '%s'", $method));
    }
}