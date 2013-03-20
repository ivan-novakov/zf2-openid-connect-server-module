<?php

namespace PhpIdServer\General\Exception;


class ClassNotFoundException extends \RuntimeException
{


    public function __construct($className)
    {
        parent::__construct(sprintf("Class not found: %s", $className));
    }
}