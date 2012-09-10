<?php
namespace PhpIdServer\Controller;
use PhpIdServer\Client\Registry;


abstract class BaseController extends \Zend\Mvc\Controller\AbstractActionController
{


    protected function _getServerConfig ()
    {
        return $this->getServiceLocator()
            ->get('serverConfig');
    }


    protected function _getClientRegistry ()
    {
        $serviceLocator = $this->getServiceLocator();
        
        if (! $this->serviceLocator->has('ClientRegistry')) {
            $storage = new Registry\Storage\SingleJsonFileStorage(array(
                'json_file' => 'data/client/metadata.json'
            ));
            $registry = new Registry\Registry($storage);
            $serviceLocator->setService('ClientRegistry', $registry);
        } else {
            $registry = $this->serviceLocator->get('ClientRegistry');
        }
        
        return $registry;
    }


    protected function _debug ($value)
    {
        $this->getServiceLocator()
            ->get('Logger')
            ->debug($value);
    }
}