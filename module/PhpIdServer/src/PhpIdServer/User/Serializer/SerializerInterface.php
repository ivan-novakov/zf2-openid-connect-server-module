<?php

namespace PhpIdServer\User\Serializer;

use PhpIdServer\User\User;


interface SerializerInterface
{


    /**
     * Serializes the user object to a string.
     * 
     * @param User $user
     * @return string
     */
    public function serialize (User $user);


    /**
     * Unserializes user object from string.
     * 
     * @param string $data
     * @return User
     */
    public function unserialize ($data);
}