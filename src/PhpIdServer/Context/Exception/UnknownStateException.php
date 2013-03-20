<?php
namespace PhpIdServer\Context\Exception;


class UnknownStateException extends \Exception
{


    public function __construct ($state)
    {
        parent::__construct(sprintf("Unknown state '%s'", $state));
    }
}