<?php

namespace PhpIdServer\Authentication\Controller;

use PhpIdServer\Authentication\Info;
use PhpIdServer\User\User;
use PhpIdServer\Context\AuthorizeContext;


class DummyController extends AbstractController
{


    protected function _authenticate (AuthorizeContext $context)
    {
        $this->_debug('Dummy authentication controller: ' . $this->getRequest()
            ->getUri());
        
        $user = new User($this->_options->get('identity'));
        $authenticationInfo = new Info(array(
            Info::FIELD_METHOD => $this->_options->get('label'), 
            Info::FIELD_TIME => new \DateTime('now')
        ));
        
        $context->setUser($user);
        $context->setAuthenticationInfo($authenticationInfo);
        
        $this->_debug('Saving context...');
        $this->_saveContext($context);
    }
}