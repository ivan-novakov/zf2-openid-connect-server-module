<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\Authentication;


class AuthorizeController extends BaseController
{

    /**
     * @var AuthorizeContext
     */
    protected $authorizeContext = null;

    /**
     * @var Dispatcher\Authorize
     */
    protected $authorizeDispatcher = null;

    /**
     * @var Authentication\Manager
     */
    protected $authenticationManager = null;

    protected $logIdent = 'authorize';


    /**
     * Sets the authorize context.
     * 
     * @param AuthorizeContext $authorizeContext
     */
    public function setAuthorizeContext(AuthorizeContext $authorizeContext)
    {
        $this->authorizeContext = $authorizeContext;
    }


    /**
     * Returns the authorize context.
     * 
     * @return AuthorizeContext
     */
    public function getAuthorizeContext()
    {
        return $this->authorizeContext;
    }


    /**
     * Sets the authorize dispatcher.
     * 
     * @param Dispatcher\Authorize $authorizeDispatcher
     */
    public function setAuthorizeDispatcher(Dispatcher\Authorize $authorizeDispatcher)
    {
        $this->authorizeDispatcher = $authorizeDispatcher;
    }


    /**
     * Returns the authorize dispatcher.
     * 
     * @return Dispatcher\Authorize
     */
    public function getAuthorizeDispatcher()
    {
        return $this->authorizeDispatcher;
    }


    /**
     * Sets the authentication manager.
     * 
     * @param Authentication\Manager $authenticationManager
     */
    public function setAuthenticationManager(Authentication\Manager $authenticationManager)
    {
        $this->authenticationManager = $authenticationManager;
    }


    /**
     * Returns the authentication manager.
     * 
     * @return Authentication\Manager
     */
    public function getAuthenticationManager()
    {
        return $this->authenticationManager;
    }


    public function indexAction()
    {
        $this->_logInfo($_SERVER['REQUEST_URI']);
        
        $response = null;
        $context = $this->getAuthorizeContext();
        $dispatcher = $this->getAuthorizeDispatcher();
        
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
                $this->saveContext($context);
            } catch (\Exception $e) {
                $response = $dispatcher->serverErrorResponse(sprintf("[%s] %s", get_class($e), $e->getMessage()));
                return $this->_errorResponse($response, 'General error in preDispatch');
            }
            
            $manager = $this->getAuthenticationManager();
            
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
        $this->clearContext();
        
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


    protected function _validResponse(Response\Authorize\Simple $response)
    {
        $this->_logInfo('dispatch OK, returning response...');
        
        return $response->getHttpResponse();
    }


    protected function _errorResponse(Response\Authorize\Error $response, $label = 'Error')
    {
        $this->_logError(sprintf("%s: %s (%s)", $label, $response->getErrorMessage(), $response->getErrorDescription()));
        
        return $response->getHttpResponse();
    }
}