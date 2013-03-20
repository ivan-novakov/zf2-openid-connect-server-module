<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher\Exception;


class InvalidRequestException extends \Exception
{


    public function __construct (Array $reasons)
    {
        parent::__construct(sprintf("Invalid request: %s", implode(', ', $reasons)));
    }
}