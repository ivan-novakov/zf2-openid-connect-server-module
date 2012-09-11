<?php

namespace PhpIdServer\Controller;

use Zend\Mvc\MvcEvent;
use PhpIdServer\Context;
use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\Authentication;


class AuthorizeController extends BaseController
{

    protected $_contextStateHandlers = array(
        AuthorizeContext::STATE_INITIAL => '_handlerValidateRequest', 
        AuthorizeContext::STATE_REQUEST_VALIDATED => '_handlerAuthenticateUser', 
        AuthorizeContext::STATE_USER_AUTHENTICATED => '_handlerUserConsent'
    );


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
        
        while (! $context->isFinalState()) {
            
            try {
                $result = $this->_processCurrentState($context);
                if ($result instanceof \Zend\Http\Response) {
                    return $result;
                }
            } catch (\Exception $e) {
                $this->_debug("$e");
                return $this->_handleError();
            }
        }
        
        $this->_debug('Authorization complete');
        
        $this->getServiceLocator()
            ->get('ContextStorage')
            ->clear();
        
        //-----------------------
        $response = $this->getResponse();
        
        $response->getHeaders()
            ->addHeaders(array(
            'Content-Type' => 'application/json'
        ));
        
        $response->setContent(\Zend\Json\Encoder::encode(array(
            'endpoint' => 'authorize'
        )));
        
        return $response;
    }


    protected function _handleError ($message = 'Error')
    {
        $response = $this->getResponse();
        $response->setStatusCode(500);
        
        return $response;
    }


    protected function _processCurrentState (AuthorizeContext $context)
    {
        $currentState = $context->getState();
        $this->_debug(sprintf("Processing state '%s'", $currentState));
        
        if (! isset($this->_contextStateHandlers[$currentState])) {
            throw new \Exception(sprintf("No handler specified for state '%s'", $currentState));
        }
        
        $handler = $this->_contextStateHandlers[$currentState];
        if (! method_exists($this, $handler)) {
            throw new Exception\UndefinedContextStateHandlerException($handler);
        }
        
        return $this->$handler($context);
    }
    
    /*
     * Context state handlers
     */
    
    /**
     * Validates the request.
     * 
     * @param AuthorizeContext $context
     */
    protected function _handlerValidateRequest (AuthorizeContext $context)
    {
        // validate request (?) - first validate and then add to context
        

        /*
         * Get client object from registry.
         */
        $clientId = $context->getRequest()
            ->getClientId();
        $registry = $this->_getClientRegistry();
        $client = $registry->getClientById($clientId);
        //_dump($client);

        // validate - check redirect_uri
        

        $this->_debug(sprintf("Loaded client ID '%s'", $clientId));
        $context->setClient($client);
        
        //_dump($context->getRequest());
        $this->_debug('SET STATE: ' . AuthorizeContext::STATE_REQUEST_VALIDATED);
        $context->setState(AuthorizeContext::STATE_REQUEST_VALIDATED);
    }


    protected function _handlerAuthenticateUser (AuthorizeContext $context)
    {
        // if not authenticated - use authentication handler and redirect, but first save the current state
        if (! $context->isUserAuthenticated()) {
            $this->_debug('user not authenticated');
            
            $manager = $this->_getAuthenticationManager();
            //_dump($manager);
            $this->_debug('redirecting user to authentication handler');
            $this->_saveContext($context);
            
            return $this->plugin('redirect')
                ->toRoute($manager->getAuthenticationRouteName());
        }
        
        $user = $context->getUser();
        $info = $context->getAuthenticationInfo();
        
        $this->_debug(sprintf("User '%s' authenticated, handler: %s, time: %s", $user->getId(), $info->getHandler(), date("c", $info->getTime())));
        
        // otherwise continue
        $context->setState(AuthorizeContext::STATE_USER_AUTHENTICATED);
    }


    protected function _handlerUserConsent (AuthorizeContext $context)
    {
        $this->_debug('handling user consent');
        
        $this->_debug('SET STATE: ' . AuthorizeContext::STATE_USER_CONSENT_APPROVED);
        $context->setState(AuthorizeContext::STATE_USER_CONSENT_APPROVED);
    }


    /**
     * Returns the context object.
     * 
     * @return AuthorizeContext
     */
    protected function _getContext ()
    {
        return $this->getServiceLocator()
            ->get('AuthorizeContext');
    }


    protected function _getAuthenticationManager ()
    {
        $config = $this->getServiceLocator()
            ->get('ServerConfig');
        $manager = new Authentication\Manager($config->authentication);
        
        return $manager;
    }


    protected function _debug ($message)
    {
        $message = sprintf("[%s] %s", $this->_getContext()
            ->getState(), $message);
        parent::_debug($message);
    }
}