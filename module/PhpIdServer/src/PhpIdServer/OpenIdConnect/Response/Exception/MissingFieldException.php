<?php

namespace PhpIdServer\OpenIdConnect\Response\Exception;


class MissingFieldException extends \Exception
{


    public function __construct ($fieldName)
    {
        parent::__construct(sprintf("Missing field '%s'", $fieldName));
    }
}