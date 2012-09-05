<?php
namespace PhpIdServer\Controller;
use Zend\Mvc\Controller\AbstractActionController;


class BaseController extends AbstractActionController
{


    protected function _getServerConfig ()
    {
        return $this->getServiceLocator()
            ->get('serverConfig');
    }
}