<?php

namespace PhpIdServer\Client\Registry;

use PhpIdServer\General\Exception;


class StorageFactory implements \Zend\ServiceManager\FactoryInterface
{

    const CONFIG_FIELD = 'client_registry_storage';


    public function createService (\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('ServerConfig')
            ->get(self::CONFIG_FIELD);
        
        if (NULL === $config) {
            throw new Exception\MissingConfigException(self::CONFIG_FIELD);
        }
        
        if (! $config->type) {
            throw new Exception\MissingParameterException('type');
        }
        
        $className = sprintf("%s\Storage\%s", __NAMESPACE__, $config->type);
        
        return new $className($config->options);
    }
}