<?php

namespace PhpIdServer\Authentication\Controller;


interface AuthenticationControllerInterface
{


    public function getLabel ();


    public function authenticateAction ();
}