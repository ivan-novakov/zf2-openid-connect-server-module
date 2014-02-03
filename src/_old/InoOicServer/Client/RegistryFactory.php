<?php

namespace InoOicServer\Client;


class RegistryFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        return new Registry\Registry($serviceLocator->get('InoOicServer\ClientRegistryStorage'));
    }
}