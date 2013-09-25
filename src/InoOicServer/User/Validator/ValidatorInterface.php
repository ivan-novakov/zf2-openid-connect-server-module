<?php

namespace InoOicServer\User\Validator;

use InoOicServer\User\UserInterface;


interface ValidatorInterface
{


    /**
     * Validates the user.
     * 
     * @param UserInterface $user
     */
    public function validate(UserInterface $user);
}