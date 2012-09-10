<?php
namespace PhpIdServer\Authentication\Controller;
use PhpIdServer\Context\AuthorizeContext;


class DummyController extends AbstractController
{


    protected function _authenticate (AuthorizeContext $context)
    {
        $this->_debug($this->getRequest()->getUri());
        
        $this->_debug('SET STATE: ' . AuthorizeContext::STATE_USER_AUTHENTICATED);
        $context->setState(AuthorizeContext::STATE_USER_AUTHENTICATED);
    }
}