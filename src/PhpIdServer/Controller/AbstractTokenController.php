<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\OpenIdConnect\Dispatcher\DispatcherInterface;


/**
 * Abstract controller superclass for all token-like endpoints - /token, /userinfo, ...
 */
abstract class AbstractTokenController extends BaseController
{

    /**
     * @var DispatcherInterface
     */
    protected $dispatcher = null;


    /**
     * @return DispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }


    /**
     * @param DispatcherInterface $dispatcher
     */
    public function setDispatcher(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    
    // FIXME - unify
    protected function _handleException(\Exception $e)
    {
        _dump("$e");
        $this->_logError(sprintf("[%s] %s", get_class($e), $e->getMessage()));
        
        $response = $this->getResponse();
        $response->setStatusCode(400);
        $response->setContent(\Zend\Json\Json::encode(array(
            'error' => 'general error',
            'error_description' => sprintf("[%s] %s", get_class($e), $e->getMessage())
        )));
        
        return $response;
    }


    /**
     * Dispatches the request using the provided dispatcher.
     * 
     * @param Dispatcher\DispatcherInterface $dispatcher
     * @return \Zend\Http\Response
     */
    protected function _dispatch(Dispatcher\DispatcherInterface $dispatcher)
    {
        try {
            $this->_logInfo('Dispatching request...');
            $tokenResponse = $dispatcher->dispatch();
            
            if ($tokenResponse->isError()) {
                $this->_logError(sprintf("Dispatch error: %s (%s)", $tokenResponse->getErrorMessage(), $tokenResponse->getErrorDescription()));
            } else {
                $this->_logInfO('Dispatch OK, returning response...');
            }
            
            $response = $tokenResponse->getHttpResponse();
        } catch (\Exception $e) {
            // FIXME - use the $oicResponse instead of the raw http response
            $response = $this->_handleException($e);
        }
        
        return $response;
    }
}