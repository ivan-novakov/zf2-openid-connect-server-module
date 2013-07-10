<?php

namespace InoOicServer\OpenIdConnect\Dispatcher\Exception;


class InvalidAuthorizationCodeException extends \Exception
{


    public function __construct ($code)
    {
        parent::__construct(sprintf("Invalid authorization code '%s'", $code));
    }
}