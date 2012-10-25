<?php

namespace PhpIdServer\Controller;

use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Dispatcher\Token;


class TokenController extends BaseController
{

    protected $_logIdent = 'token';


    public function indexAction ()
    {
        $this->_logInfo($_SERVER['REQUEST_URI']);
        
        $serviceManager = $this->_getServiceManager();
        
        $dispatcher = $serviceManager->get('TokenDispatcher');
        
        try {
            $this->_logInfo('Dispatching token request...');
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