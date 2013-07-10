<?php

namespace InoOicServer\Authentication\Controller;

use InoOicServer\User\UserFactoryInterface;


interface AuthenticationControllerInterface
{


    public function getLabel ();


    public function setUserFactory (UserFactoryInterface $userFactory);


    public function authenticateAction ();
}