<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Dispatcher;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;


class UserinfoController extends BaseController
{


    public function indexAction ()
    {
        $serviceLocator = $this->getServiceLocator();
        
        $dispatcher = new Dispatcher\UserInfo();
        $dispatcher->setSessionManager($serviceLocator->get('SessionManager'));
        $dispatcher->setUserInfoRequest(new Request\UserInfo($this->getRequest()));
        $dispatcher->setUserInfoResponse(new Response\UserInfo($this->getResponse()));
        
        try {
            $oicResponse = $dispatcher->dispatch();
            $response = $oicResponse->getHttpResponse();
        } catch (\Exception $e) {
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
            'error' => 'general error'
        )));
        
        return $response;
    }
}