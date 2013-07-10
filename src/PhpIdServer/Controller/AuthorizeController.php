<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\Authentication;
use PhpIdServer\Context\AuthorizeContextManager;
use PhpIdServer\General\Exception\MissingDependencyException;


class AuthorizeController extends BaseController
{

    /**
     * Authorize context manager.
     * @var AuthorizeContextManager
     */
    protected $authorizeContextManager;

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
     * @return AuthorizeContextManager
     */
    public function getAuthorizeContextManager($throwException = false)
    {
        if (! $this->authorizeContextManager instanceof AuthorizeContextManager && $throwException) {
            throw new MissingDependencyException('authorize context manager');
        }
        return $this->authorizeContextManager;
    }


    /**
     * @param AuthorizeContextManager $authorizeContextManager
     */
    public function setAuthorizeContextManager(AuthorizeContextManager $authorizeContextManager)
    {
        $this->authorizeContextManager = $authorizeContextManager;
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
        $this->logInfo($_SERVER['REQUEST_URI']);
        
        $response = null;
        
        $contextManager = $this->getAuthorizeContextManager();
        $context = $contextManager->initContext();
        
        $dispatcher = $this->getAuthorizeDispatcher();
        $dispatcher->setContext($context);
        
        /*
         * User authentication
         */
        if (! $context->isUserAuthenticated()) {
            
            $this->logInfo('user not authenticated - running preDispatch()');
            
            try {
                $response = $dispatcher->preDispatch();
                if ($response instanceof Response\Authorize\Error) {
                    return $this->errorResponse($response, 'Error in preDispatch()');
                }
                // $this->saveContext($context);
                $this->logInfo('preDispatch OK');
            } catch (\Exception $e) {
                $response = $dispatcher->serverErrorResponse(sprintf("[%s] %s", get_class($e), $e->getMessage()));
                return $this->errorResponse($response, 'General error in preDispatch');
            }
            
            // ???
            $contextManager->persistContext($context);
            
            $manager = $this->getAuthenticationManager();
            $manager->setContext($context);
            
            $authenticationHandlerName = $manager->getAuthenticationHandler();
            $this->logInfo(sprintf("redirecting user to authentication handler [%s]", $authenticationHandlerName));
            
            return $this->redirectToRoute($manager->getAuthenticationRouteName(), array(
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
        // $this->clearContext();
        $contextManager->unpersistContext();
        
        /*
         * Dispatching response
         */
        try {
            $this->logInfo('dispatching response...');
            
            $response = $dispatcher->dispatch();
            if ($response instanceof Response\Authorize\Error) {
                return $this->errorResponse($response, 'Error in dispatch');
            }
        } catch (\Exception $e) {
            $response = $dispatcher->serverErrorResponse(sprintf("[%s] %s", get_class($e), $e->getMessage()));
            return $this->errorResponse($response, 'General error in dispatch');
        }
        
        return $this->validResponse($response);
    }


    protected function validResponse(Response\Authorize\Simple $response)
    {
        $this->logInfo('dispatch OK, returning response...');
        return $response->getHttpResponse();
    }


    protected function errorResponse(Response\Authorize\Error $response, $label = 'Error')
    {
        // $this->clearContext();
        $this->getAuthorizeContextManager()->unpersistContext();
        $this->logError(sprintf("%s: %s (%s)", $label, $response->getErrorMessage(), $response->getErrorDescription()));
        return $response->getHttpResponse();
    }
}