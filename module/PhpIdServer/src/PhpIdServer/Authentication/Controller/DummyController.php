<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\User;
use PhpIdServer\Context\AuthorizeContext;


class DummyController extends AbstractController
{


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Authentication\Controller\AbstractController::authenticate()
     */
    public function authenticate ()
    {
        $user = new User($this->getOption('identity'));
        
        return $user;
    }
}