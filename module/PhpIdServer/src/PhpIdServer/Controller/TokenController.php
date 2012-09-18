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
            $response = $dispatcher->dispatch();
        } catch (\Exception $e) {
            $response = $this->_handleException($e);
        }
        _dump($response);
        
        $response = $this->getResponse();
        
        $response->getHeaders()
            ->addHeaders(array(
            'Content-Type' => 'application/json'
        ));
        
        $response->setContent(\Zend\Json\Encoder::encode(array(
            'endpoint' => 'token'
        )));
        
        return $response;
    }
}