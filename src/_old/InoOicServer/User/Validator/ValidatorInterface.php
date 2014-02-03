<?php

namespace InoOicServer\User\Validator;

use Zend\Session;
use InoOicServer\User\UserInterface;


interface ValidatorInterface
{


    /**
     * Validates the user.
     * 
     * @param UserInterface $user
     */
    public function validate(UserInterface $user);


    /**
     * Sets the session container.
     * 
     * @param Session\Container $sessionContainer
     */
    public function setSessionContainer(Session\Container $sessionContainer);
}