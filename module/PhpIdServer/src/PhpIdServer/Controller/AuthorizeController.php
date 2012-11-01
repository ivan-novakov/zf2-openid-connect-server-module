<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Response;


class AuthorizeController extends BaseController
{

    protected $_logIdent = 'authorize';


    public function indexAction ()
    {
        $this->_logInfo($_SERVER['REQUEST_URI']);
        
        $response = null;
        $serviceManager = $this->_getServiceManager();
        
        $context = $serviceManager->get('AuthorizeContext');
        /* @var $context \PhpIdServer\Context\Authorize */
        
        $dispatcher = $serviceManager->get('AuthorizeDispatcher');
        /* @var $dispatcher \PhpIdServer\OpenIdConnect\Dispatcher\Authorize */
        
        /*
         * User authentication
         */
        if (! $context->isUserAuthenticated()) {
            
            $this->_logInfo('user not authenticated - running preDispatch()');
            
            try {
                $response = $dispatcher->preDispatch();
                if ($response instanceof Response\Authorize\Error) {
                    return $this->_errorResponse($response, 'Error in preDispatch()');
                }
                
                $this->_logInfo('preDispatch OK');
                $this->_saveContext($context);
            } catch (\Exception $e) {
                $response = $dispatcher->serverErrorResponse(sprintf("[%s] %s", get_class($e), $e->getMessage()));
                return $this->_errorResponse($response, 'General error in preDispatch');
            }
            
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
            if ($response instanceof Response\Authorize\Error) {
                return $this->_errorResponse($response, 'Error in dispatch');
            }
        } catch (\Exception $e) {
            $response = $dispatcher->serverErrorResponse(sprintf("[%s] %s", get_class($e), $e->getMessage()));
            return $this->_errorResponse($response, 'General error in dispatch');
        }
        
        return $this->_validResponse($response);
    }


    protected function _validResponse (Response\Authorize\Simple $response)
    {
        $this->_logInfo('dispatch OK, returning response...');
        
        return $response->getHttpResponse();
    }


    protected function _errorResponse (Response\Authorize\Error $response, $label = 'Error')
    {
        $this->_logError(sprintf("%s: %s (%s)", $label, $response->getErrorMessage(), $response->getErrorDescription()));
        
        return $response->getHttpResponse();
    }
}