<?php

namespace InoOicServer\Controller;

use Zend\Session;
use InoOicServer\OpenIdConnect\Response;
use InoOicServer\OpenIdConnect\Dispatcher;
use InoOicServer\Authentication;
use InoOicServer\Context\AuthorizeContextManager;
use InoOicServer\General\Exception\MissingDependencyException;
use InoOicServer\User\Validator\Exception\InvalidUserException;


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
    protected $authorizeDispatcher;

    /**
     * @var Authentication\Manager
     */
    protected $authenticationManager;

    /**
     * Session container.
     * @var Session\Container
     */
    protected $sessionContainer;

    protected $logIdent = 'authorize';


    /**
     * @return Session\Container
     */
    public function getSessionContainer()
    {
        return $this->sessionContainer;
    }


    /**
     * @param Session\Container $sessionContainer
     */
    public function setSessionContainer(Session\Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }


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


    public function authorizeAction()
    {
        $this->logInfo($_SERVER['REQUEST_URI']);
        
        $this->getSessionContainer()->offsetSet('http_request', $this->getRequest());
        
        $response = null;
        
        $contextManager = $this->getAuthorizeContextManager();
        
        /*
         * Check if the user has been already authenticated
         */
        $existingContext = $contextManager->loadContext();
        if ($existingContext && ($user = $existingContext->getUser())) {
            $authenticationInfo = $existingContext->getAuthenticationInfo();
            $authTime = $authenticationInfo->getTime();
            $expireTime = new \DateTime("+1 hour");
            // $expireTime = new \DateTime('@' .(time() + 3600));
            if ($authTime < $expireTime) {
                
                $contextManager->updateContextRequest($existingContext);
                $contextManager->persistContext($existingContext);
                
                // redirect to dispatch
                $this->logInfo(sprintf("User '%s' has been logged in at %s, skipping authentication...", $user->getId(), $authTime->format('c')));
                
                $authorizeRoute = 'php-id-server/authorize-response-endpoint';
                $this->logInfo(sprintf("redirecting to response endpoint '%s'", $authorizeRoute));
                
                return $this->redirectToRoute($authorizeRoute);
            }
            
            $this->logInfo(sprintf("Existing context for user '%s' found, but it has expired", $user->getId()));
        }
        
        $context = $contextManager->initContext();
        
        $dispatcher = $this->getAuthorizeDispatcher();
        $dispatcher->setContext($context);
        
        $this->logInfo('user not authenticated - running preDispatch()');
        
        try {
            $response = $dispatcher->preDispatch();
            if ($response instanceof Response\Authorize\Error) {
                return $this->errorResponse($response, 'Error in preDispatch()');
            }
            
            $this->logInfo('preDispatch OK');
        } catch (\Exception $e) {
            $response = $dispatcher->serverErrorResponse(sprintf("[%s] %s", get_class($e), $e->getMessage()));
            return $this->errorResponse($response, 'General error in preDispatch');
        }
        
        $contextManager->persistContext($context);
        
        $manager = $this->getAuthenticationManager();
        $manager->setContext($context);
        
        $authenticationHandlerName = $manager->getAuthenticationHandler();
        $this->logInfo(sprintf("redirecting user to authentication handler [%s]", $authenticationHandlerName));
        
        return $this->redirectToRoute($manager->getAuthenticationRouteName(), array(
            'controller' => $authenticationHandlerName
        ));
    }


    public function responseAction()
    {
        $this->logInfo($_SERVER['REQUEST_URI']);
        
        $response = null;
        
        $contextManager = $this->getAuthorizeContextManager();
        $context = $contextManager->initContext();
        // $contextManager->unpersistContext();
        
        $dispatcher = $this->getAuthorizeDispatcher();
        $dispatcher->setContext($context);
        
        try {
            $this->logInfo('dispatching response...');
            
            $response = $dispatcher->dispatch();
            
            if ($response instanceof Response\Authorize\Error) {
                return $this->errorResponse($response, 'Error in dispatch');
            }
        } catch (\Exception $e) {
            if ($e instanceof InvalidUserException && $redirectUri = $e->getRedirectUri()) {
                $this->logError(sprintf("Invalid user: [%s] %s - redirecting to '%s'", get_class($e), $e->getMessage(), $redirectUri));
                
                return $this->redirect()->toUrl($redirectUri);
            }
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