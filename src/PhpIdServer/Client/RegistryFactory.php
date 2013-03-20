<?php

namespace PhpIdServer\Client;

use PhpIdServer\General\Exception;


class RegistryFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return new Registry\Registry($serviceLocator->get('ClientRegistryStorage'));
    }
}