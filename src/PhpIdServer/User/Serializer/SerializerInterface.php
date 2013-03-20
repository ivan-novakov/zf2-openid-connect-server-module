<?php

namespace PhpIdServer\User\Serializer;

use PhpIdServer\User\UserInterface;


interface SerializerInterface
{


    /**
     * Serializes the user object into a string.
     * 
     * @param UserInterface $user
     * @return string
     */
    public function serialize (UserInterface $user);


    /**
     * Unserializes user data and returns the user object.
     * 
     * @param string $data
     * @return UserInterface
     */
    public function unserialize ($data);
}