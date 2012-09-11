<?php

namespace PhpIdServer\Session\IdGenerator\Exception;


class MissingValueException extends \Exception
{


    public function __construct ($paramName)
    {
        parent::__construct(sprintf("Missing value for parameter '%s'", $paramName));
    }
}