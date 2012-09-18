<?php

namespace PhpIdServer\General\Exception;


class MissingDependencyException extends \Exception
{


    public function __construct ($dependency)
    {
        parent::__construct(sprintf("Missing dependency '%s'", $dependency));
    }
}