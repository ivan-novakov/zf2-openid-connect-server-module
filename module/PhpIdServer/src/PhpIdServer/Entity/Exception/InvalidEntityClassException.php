<?php

namespace PhpIdServer\Entity\Exception;


class InvalidEntityClassException extends \Exception
{


    public function __construct ($className)
    {
        parent::__construct(sprintf("Invalid entity class '%s'", $className));
    }
}