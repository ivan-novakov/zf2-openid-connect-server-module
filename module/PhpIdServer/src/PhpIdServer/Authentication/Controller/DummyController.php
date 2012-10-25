<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\User\User;
use PhpIdServer\Context\AuthorizeContext;


class DummyController extends AbstractController
{


    protected function _authenticate (AuthorizeContext $context)
    {
        $user = new User($this->getOption('identity'));
        
        $authenticationInfo = $this->_initAuthenticationInfo();
        
        $context->setUser($user);
        $context->setAuthenticationInfo($authenticationInfo);
        
        $this->_debug('Saving context...');
        $this->_saveContext($context);
    }
}