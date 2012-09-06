<?php
namespace PhpIdServer\Controller;


abstract class BaseController extends \Zend\Mvc\Controller\AbstractActionController
{


    protected function _getServerConfig ()
    {
        return $this->getServiceLocator()
            ->get('serverConfig');
    }


    protected function _debug ($value)
    {
        $this->getServiceLocator()
            ->get('Logger')
            ->debug($value);
    }
}