<?php

namespace PhpIdServer\Controller;

use Zend\Mvc\MvcEvent;
use PhpIdServer\Context;
use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\Authentication;
use PhpIdServer\OpenIdConnect;
use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Request;


class AuthorizeController extends BaseController
{


    public function indexAction ()
    {
        $serviceLocator = $this->getServiceLocator();
        $context = $serviceLocator->get('AuthorizeContext');
        
        $dispatcher = new Dispatcher\Authorize();
        $dispatcher->setContext($context);
        
        $oicResponse = new Response\Authorize\Simple($this->getResponse());
        $dispatcher->setAuthorizeResponse($oicResponse);
        
        $dispatcher->setClientRegistry($serviceLocator->get('ClientRegistry'));
        $dispatcher->setSessionManager($serviceLocator->get('SessionManager'));
        
        /*
         * User authentication
         */
        if (! $context->isUserAuthenticated()) {
            $this->_debug('user not authenticated - running preDispatch()');
            
            try {
                $response = $dispatcher->preDispatch();
                if ($response instanceof Response\Authorize\Error) {
                    $this->_debug('error during preDispatch() phase');
                    return $response->getHttpResponse();
                }
            } catch (\Exception $e) {
                _dump("$e");
                $this->_debug("$e");
                return $this->_handleError();
            }
            
            $manager = $this->_getAuthenticationManager();
            
            $this->_debug('redirecting user to authentication handler');
            $this->_saveContext($context);
            
            return $this->_redirectToRoute($manager->getAuthenticationRouteName());
        }
        
        /*
         * User consent
         */
        // [...]
        

        /*
         * Clear context (!)
         */
        $serviceLocator->get('ContextStorage')
            ->clear();
        
        /*
         * Dispatching response
         */
        try {
            $response = $dispatcher->dispatch();
            
            $this->_debug('returning response');
            $httpResponse = $response->getHttpResponse();
            
            return $httpResponse;
        } catch (\Exception $e) {
            _dump("$e");
            $this->_debug("$e");
            return $this->_handleError();
        }
    }


    /**
     * Returns the context object.
     * 
     * @return AuthorizeContext
     */
    protected function _getContext ()
    {
        return $this->_getServiceManager()
            ->get('AuthorizeContext');
    }


    protected function _getAuthenticationManager ()
    {
        return $this->_getServiceManager()
            ->get('AuthenticationManager');
    }
}