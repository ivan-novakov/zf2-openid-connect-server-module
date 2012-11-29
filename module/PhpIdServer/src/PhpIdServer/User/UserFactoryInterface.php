<?php

namespace PhpIdServer\User;


interface UserFactoryInterface
{


    /**
     * Creates a user entity instance.
     * 
     * @param array $userData
     * @return UserInterface
     */
    public function createUser (array $userData);
}