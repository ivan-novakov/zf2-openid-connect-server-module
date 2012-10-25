<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Response;


class AuthorizeController extends BaseController
{


    public function indexAction ()
    {
        $serviceManager = $this->_getServiceManager();
        
        $context = $serviceManager->get('AuthorizeContext');
        $dispatcher = $serviceManager->get('AuthorizeDispatcher');
        
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
            
            $manager = $serviceManager->get('AuthenticationManager');
            
            $this->_debug('redirecting user to authentication handler');
            $this->_saveContext($context);
            
            return $this->_redirectToRoute($manager->getAuthenticationRouteName(), array(
                'controller' => 'dummy'
            ));
        }
        
        /*
         * User consent
         */
        // [...]
        

        /*
         * Clear context (!)
         */
        $serviceManager->get('ContextStorage')
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
}