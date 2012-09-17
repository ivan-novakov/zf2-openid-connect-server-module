<?php

namespace PhpIdServer\User\Serializer;


class SerializerFactory implements \Zend\ServiceManager\FactoryInterface
{


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('ServerConfig')
            ->get('user_serializer');
        
        if (NULL === $config) {
            throw new \Exception(sprintf("User serializer not configured, missing 'user_serializer' config field."));
        }
        
        return new Serializer($config);
    }
}