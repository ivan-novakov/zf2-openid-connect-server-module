<?php

namespace PhpIdServer\Session\Storage\Exception;


class SimilarSessionExistsException extends \RuntimeException
{


    public function __construct ($userId, $clientId)
    {
        parent::__construct(sprintf("Session for user ID '%s' and client ID '%s' already exists", $userId, $clientId));
    }
}