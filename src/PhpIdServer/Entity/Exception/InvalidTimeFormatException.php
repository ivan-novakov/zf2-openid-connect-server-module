<?php

namespace PhpIdServer\Entity\Exception;


class InvalidTimeFormatException extends \RuntimeException
{


    public function __construct ($timeString, $message)
    {
        parent::__construct(sprintf("Invalid time format '%s': %s", $timeString, $message));
    }
}