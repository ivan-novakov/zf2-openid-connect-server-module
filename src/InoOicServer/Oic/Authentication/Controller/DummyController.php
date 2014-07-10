<?php

namespace InoOicServer\Oic\Authentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class DummyController extends AbstractActionController
{


    public function authenticateAction()
    {
        $response = $this->getResponse();
        $response->setContent('Dummy AUTHENTICATE');
        
        return $response;
    }
}