<?php

namespace InoOicServer\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class AuthorizeController extends AbstractActionController
{


    public function authorizeAction()
    {
        // create authorize request from http request
        
        // process authorize request
        
        // handle result, which can be one of these:
        // - redirect to response endpoint (if there is valid context)
        // - redirect to authentication
        // - error, client known - error redirect to client
        // - error, client not known - show HTML message directly
        return $this->getResponse();
    }


    public function responseAction()
    {
        // process authorize response
        
        // handle result, which can be one of these:
        // - redirect to client (valid response)
        // - custom redirect (to registration form)
        // - error, client known - error redirect to client
        // - error, client not known - show HTML message directly
    }
}