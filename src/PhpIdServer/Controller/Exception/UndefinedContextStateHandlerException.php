<?php
namespace PhpIdServer\Controller\Exception;


class UndefinedContextStateHandlerException extends \Exception
{


    public function __construct ($handler)
    {
        parent::__construct(sprintf("Undefined context state handler '%s'", $handler));
    }
}