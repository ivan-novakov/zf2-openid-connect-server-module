<?php

namespace PhpIdServer\User\Exception;


class UnknownUserClassException extends \RuntimeException
{


    public function __construct ($userClass)
    {
        parent::__construct(sprintf("Unknown user class '%s'", $userClass));
    }
}