<?php
namespace PhpIdServer\Controller;
use Zend\Mvc\MvcEvent;
use PhpIdServer\Context;
use PhpIdServer\Context\AuthorizeContext;


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
    protected function _handlerValidateRequest (AuthorizeContext $context)
    {
        //_dump($context->getRequest());
        $this->_debug('SET STATE: ' . AuthorizeContext::STATE_REQUEST_VALIDATED);
        $context->setState(AuthorizeContext::STATE_REQUEST_VALIDATED);
    }


    protected function _handlerAuthenticateUser (AuthorizeContext $context)
    {
        // if not authenticated - use authentication handler and redirect, but first save the current state
        $this->_debug('redirect to authentication');
        $this->getServiceLocator()
            ->get('ContextStorage')
            ->save($context);
        
        return $this->plugin('redirect')
            ->toRoute('php-id-server/authentication-endpoint');
        
        // otherwise continue
        $context->setState(AuthorizeContext::STATE_USER_AUTHENTICATED);
    }


    protected function _handlerUserConsent (AuthorizeContext $context)
    {
        $this->_debug('SET STATE: ' . AuthorizeContext::STATE_USER_CONSENT_APPROVED);
        $context->setState(AuthorizeContext::STATE_USER_CONSENT_APPROVED);
    }
}