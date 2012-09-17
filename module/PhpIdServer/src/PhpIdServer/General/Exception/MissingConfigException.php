<?php

namespace PhpIdServer\General\Exception;


class MissingConfigException extends \Exception
{


    public function __construct ($configFieldName)
    {
        parent::__construct(sprintf("Missing config field '%s'", $configFieldName));
    }
}