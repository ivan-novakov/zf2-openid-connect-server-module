<?php

namespace InoOicServer\General\Exception;


class MissingParameterException extends \Exception
{


    public function __construct ($paramName)
    {
        parent::__construct(sprintf("Missing value for parameter '%s'", $paramName));
    }
}