<?php

namespace PhpIdServer\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController
{


    public function indexAction()
    {
        $response = $this->getResponse();
        $response->setContent('INDEX');
        
        return $response;
    }


    public function fooAction()
    {
        $response = $this->getResponse();
        $response->setContent('FOO');
        
        return $response;
    }
}
