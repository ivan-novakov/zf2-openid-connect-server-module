<?php

namespace PhpIdServer\User\DataConnector;

use PhpIdServer\User\User;
use PhpIdServer\User\UserInterface;


class Dummy extends AbstractDataConnector
{


    public function populateUser (UserInterface $user)
    {
        $user->setName($user->getName() . ' (dummy)');
    }
}