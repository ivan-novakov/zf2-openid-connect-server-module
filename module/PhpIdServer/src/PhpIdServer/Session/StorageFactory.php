<?php

namespace PhpIdServer\Session;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;


class StorageFactory implements FactoryInterface
{


    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('ServerConfig')->get('session_storage');
    }
}