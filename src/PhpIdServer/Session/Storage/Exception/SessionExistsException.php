<?php

namespace PhpIdServer\Session\Storage\Exception;


class SessionExistsException extends \RuntimeException
{


    public function __construct ($sessionId)
    {
        parent::__construct(sprintf("Session with ID '%s' already exists", $sessionId));
    }
}