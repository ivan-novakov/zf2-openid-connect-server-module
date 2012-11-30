<?php

namespace PhpIdServer\User\DataConnector;

use PhpIdServer\User\UserInterface;


interface DataConnectorInterface
{


    /**
     * Populates the user entity with additional data.
     * 
     * @param UserInterface $user
     */
    public function populateUser (UserInterface $user);
}

