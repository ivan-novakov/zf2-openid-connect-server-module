<?php

namespace PhpIdServer\Client\Authentication\Method\Exception;


class InvalidAuthenticationMethodException extends \RuntimeException
{


    public function __construct($methodName)
    {
        parent::__construct(sprintf("Invalid client authentication method '%s'", $methodName));
    }
}