<?php

namespace PhpIdServer\User\Serializer;

use PhpIdServer\User\User;


interface SerializerInterface
{


    /**
     * Serializes the user object into a string.
     * 
     * @param User $user
     * @return string
     */
    public function serialize (User $user);


    /**
     * Unserializes user data and returns the user object.
     * 
     * @param string $data
     * @return User
     */
    public function unserialize ($data);
}