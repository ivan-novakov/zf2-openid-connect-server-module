<?php

namespace PhpIdServer\Session\IdGenerator;

use PhpIdServer\Client\Client;
use PhpIdServer\User\User;


/**
 * The ID generator simply serializes the user ID, the client ID, adds the current time and a secret salt and
 * calculates the MD5 sum.
 *
 */
class Simple extends AbstractIdGenerator
{


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\IdGenerator\IdGeneratorInterface::generateId()
     */
    public function generateId (User $user = NULL, Client $client = NULL)
    {
        $secretSalt = $this->_options->get('secret_salt');
        if (! $secretSalt) {
            throw new Exception\MissingValueException('secret_salt');
        }
        
        return md5($user->getId() . $client->getId() . $this->_options->get('time', time()) . $this->_options->get('secret_salt'));
    }
}