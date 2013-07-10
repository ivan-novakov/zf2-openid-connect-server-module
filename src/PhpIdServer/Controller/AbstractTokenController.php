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
    protected function handleException(\Exception $e)
    {
        _dump("$e");
        $this->logError(sprintf("[%s] %s", get_class($e), $e->getMessage()));
        
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
    protected function dispatchTokenRequest(Dispatcher\DispatcherInterface $dispatcher)
    {
        try {
            $this->logInfo('Dispatching request...');
            $tokenResponse = $dispatcher->dispatch();
            
            if ($tokenResponse->isError()) {
                $this->logError(sprintf("Dispatch error: %s (%s)", $tokenResponse->getErrorMessage(), $tokenResponse->getErrorDescription()));
            } else {
                $this->logInfO('Dispatch OK, returning response...');
            }
            
            $response = $tokenResponse->getHttpResponse();
        } catch (\Exception $e) {
            // FIXME - use the $oicResponse instead of the raw http response
            $response = $this->handleException($e);
        }
        
        return $response;
    }
}