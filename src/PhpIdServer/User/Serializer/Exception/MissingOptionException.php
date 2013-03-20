<?php

namespace PhpIdServer\User\Serializer\Exception;


class MissingOptionException extends \Exception
{


    public function __construct ($optionName)
    {
        parent::__construct(sprintf("Missing serializer option '%s'", $optionName));
    }
}