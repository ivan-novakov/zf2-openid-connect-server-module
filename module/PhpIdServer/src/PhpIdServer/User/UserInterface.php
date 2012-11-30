<?php

namespace PhpIdServer\User;


interface UserInterface
{


    /**
     * Returns the user ID.
     * 
     * @return integer
     */
    public function getId ();


    /**
     * Populates the user entity with data.
     * 
     * @param array $data
     */
    public function populate (array $data);


    /**
     * Returns the array representation of the user.
     * 
     * @return array
     */
    public function toArray ();
}