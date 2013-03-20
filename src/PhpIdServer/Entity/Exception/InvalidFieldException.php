<?php

namespace PhpIdServer\Entity\Exception;


class InvalidFieldException extends \RuntimeException
{


    public function __construct ($fieldName, $entityName)
    {
        parent::__construct(sprintf("Invalid field name '%s' for entity '%s'", $fieldName, $entityName));
    }
}