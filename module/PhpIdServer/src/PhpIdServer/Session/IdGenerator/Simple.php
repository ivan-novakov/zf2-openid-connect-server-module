<?php

namespace PhpIdServer\Session\IdGenerator;

use PhpIdServer\Client\Client;
use PhpIdServer\User\User;


class Simple extends AbstractIdGenerator
{


    public function generateId (User $user = NULL, Client $client = NULL)
    {
        $secretSalt = $this->_options->get('secret_salt');
        if (! $secretSalt) {
            throw new Exception\MissingValueException('secret_salt');
        }
        
        return md5($user->getId() . $client->getId() . $this->_options->get('time', time()) . $this->_options->get('secret_salt'));
    }
}