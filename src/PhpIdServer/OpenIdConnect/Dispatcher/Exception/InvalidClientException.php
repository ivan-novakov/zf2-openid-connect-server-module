<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher\Exception;


class InvalidClientException extends \Exception
{


    public function __construct ($clientId)
    {
        parent::__construct(sprintf("Invalid client ID '%s'", $clientId));
    }
}