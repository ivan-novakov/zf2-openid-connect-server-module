<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Response;


class AuthorizeController extends BaseController
{

    protected $_logIdent = 'authorize';


    public function indexAction ()
    {
        $this->_logInfo($_SERVER['REQUEST_URI']);
        
        $serviceManager = $this->_getServiceManager();
        
        $context = $serviceManager->get('AuthorizeContext');
        $dispatcher = $serviceManager->get('AuthorizeDispatcher');
        
        /*
         * User authentication
         */
        if (! $context->isUserAuthenticated()) {
            $this->_logInfo('user not authenticated - running preDispatch()');
            
            try {
                $response = $dispatcher->preDispatch();
                if ($response instanceof Response\Authorize\Error) {
                    $this->_logError(sprintf("preDispatch() error: %s (%s)", $response->getErrorMessage(), $response->getErrorDescription()));
                    return $response->getHttpResponse();
                }
            } catch (\Exception $e) {
                return $this->_handleException($e, 'preDispatch() exception');
            }
            
            $this->_logInfo('preDispatch() OK');
            
            $this->_saveContext($context);
            
            $manager = $serviceManager->get('AuthenticationManager');
            
            $authenticationHandlerName = $manager->getAuthenticationHandler();
            $this->_logInfo(sprintf("redirecting user to authentication handler [%s]", $authenticationHandlerName));
            
            return $this->_redirectToRoute($manager->getAuthenticationRouteName(), array(
                'controller' => $authenticationHandlerName
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
            $this->_logInfo('dispatching response...');
            $response = $dispatcher->dispatch();
            $this->_logInfo('dispatch OK, returning response...');
            $httpResponse = $response->getHttpResponse();
        } catch (\Exception $e) {
            return $this->_handleException($e, 'Dispatch exception');
        }
        
        return $httpResponse;
    }
}