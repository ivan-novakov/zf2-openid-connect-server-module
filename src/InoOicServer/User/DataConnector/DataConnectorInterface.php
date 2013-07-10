<?php

namespace InoOicServer\User\DataConnector;

use InoOicServer\User\UserInterface;


interface DataConnectorInterface
{


    /**
     * Populates the user entity with additional data.
     * 
     * @param UserInterface $user
     */
    public function populateUser (UserInterface $user);
}

