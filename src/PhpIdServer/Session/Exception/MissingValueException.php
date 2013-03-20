<?php

namespace PhpIdServer\Session\Exception;


class MissingValueException extends \Exception
{


    public function __construct ($paramName)
    {
        parent::__construct(sprintf("Missing value for parameter '%s'", $paramName));
    }
}