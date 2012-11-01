<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\User;
use PhpIdServer\Context\AuthorizeContext;


class DummyController extends AbstractController
{


    protected function _authenticate (AuthorizeContext $context)
    {
        $user = new User($this->getOption('identity'));
        
        //throw new Exception\AuthenticationException('error_name', 'error_desc');
        
        return $user;
    }
}