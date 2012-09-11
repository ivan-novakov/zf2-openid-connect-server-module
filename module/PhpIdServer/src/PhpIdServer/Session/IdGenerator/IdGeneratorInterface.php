<?php

namespace PhpIdServer\Session\IdGenerator;

use PhpIdServer\Client\Client;
use PhpIdServer\User\User;


interface IdGeneratorInterface
{


    /**
     * Generates a unique session ID. Optionally the user and client info may be used.
     * 
     * @param User $user
     * @param Client $client
     * @return string
     */
    public function generateId (User $user = NULL, Client $client = NULL);
}