<?php

namespace InoOicServer\Oic\Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class BasicAuthController extends AbstractActionController
{


    public function authenticateAction()
    {
        $response = $this->getResponse();
        $response->setContent('AUTHENTICATE');
        
        return $response;
    }
}