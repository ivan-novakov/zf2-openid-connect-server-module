<?php

namespace InoOicServer\Session\Exception;


class MissingComponentException extends \RuntimeException
{


    public function __construct ($component)
    {
        parent::__construct(sprintf("Missing component '%s'", $component));
    }
}