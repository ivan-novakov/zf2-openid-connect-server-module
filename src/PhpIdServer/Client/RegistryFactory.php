<?php

namespace PhpIdServer\Client;


class RegistryFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return new Registry\Registry($serviceLocator->get('PhpIdServer\ClientRegistryStorage'));
    }
}