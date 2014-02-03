<?php

namespace InoOicServer\User\DataConnector;

use InoOicServer\User\UserInterface;


class Dummy extends AbstractDataConnector
{


    public function populateUser(UserInterface $user)
    {
        $user->setName($user->getName() . ' (dummy)');
    }
}