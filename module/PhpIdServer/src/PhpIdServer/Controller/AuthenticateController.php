<?php
namespace PhpIdServer\Controller;
use Zend\Mvc\MvcEvent;
use PhpIdServer\Context;
use PhpIdServer\Context\AuthorizeContext;


class AuthenticateController extends BaseController
{


    public function onDispatch (MvcEvent $e)
    {
        $authorizeContextFactory = new Context\AuthorizeContextFactory();
        $authorizeContextFactory->createService($this->getServiceLocator());
        
        parent::onDispatch($e);
    }


    public function indexAction ()
    {
        $context = $this->getServiceLocator()
            ->get('AuthorizeContext');
        
        $this->_debug('SET STATE: ' . AuthorizeContext::STATE_USER_AUTHENTICATED);
        $context->setState(AuthorizeContext::STATE_USER_AUTHENTICATED);
        
        $this->_debug('redirecting back to authorize endpoint');
        return $this->plugin('redirect')
            ->toRoute('php-id-server/authorize-endpoint');
    }
}