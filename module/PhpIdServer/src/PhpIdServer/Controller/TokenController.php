<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Dispatcher\Token;


class TokenController extends BaseController
{


    public function indexAction ()
    {
        $serviceLocator = $this->getServiceLocator();
        
        $dispatcher = new Token();
        $dispatcher->setClientRegistry($serviceLocator->get('ClientRegistry'));
        $dispatcher->setSessionManager($serviceLocator->get('SessionManager'));
        
        $oicRequest = new Request\Token($this->getRequest());
        $dispatcher->setTokenRequest($oicRequest);
        
        $oicResponse = new Response\Token($this->getResponse());
        $dispatcher->setTokenResponse($oicResponse);
        
        try {
            $tokenResponse = $dispatcher->dispatch();
            $response = $tokenResponse->getHttpResponse();
        } catch (\Exception $e) {
            // FIXME - use the $oicResponse instead of the raw http response
            $response = $this->_handleException($e);
        }
        //_dump($response->getContent());
        

        return $response;
    }


    protected function _handleException (\Exception $e)
    {
        _dump("$e");
        $this->_debug("$e");
        
        $response = $this->getResponse();
        $response->setStatusCode(400);
        $response->setContent(\Zend\Json\Json::encode(array(
            'error' => 'general error', 
            'error_description' => sprintf("[%s] %s", get_class($e), $e->getMessage())
        )));
        
        return $response;
    }
}