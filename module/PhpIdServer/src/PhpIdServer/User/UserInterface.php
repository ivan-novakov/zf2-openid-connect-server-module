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
     * Returns the array representation of the user.
     * 
     * @return array
     */
    public function toArray ();
}