<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\UserFactoryInterface;


interface AuthenticationControllerInterface
{


    public function getLabel ();


    public function setUserFactory (UserFactoryInterface $userFactory);


    public function authenticateAction ();
}