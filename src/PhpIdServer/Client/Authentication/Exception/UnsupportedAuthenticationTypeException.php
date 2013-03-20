<?php
namespace PhpIdServer\Client\Authentication\Exception;


class UnsupportedAuthenticationTypeException extends \Exception
{


    public function __construct ($type)
    {
        parent::__construct(sprintf("Unsupported authentication type '%s'", $type));
    }
}