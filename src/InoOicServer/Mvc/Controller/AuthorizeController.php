<?php

namespace InoOicServer\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class AuthorizeController extends AbstractActionController
{


    public function authorizeAction()
    {
        return $this->getResponse();
    }


    public function responseAction()
    {}
}