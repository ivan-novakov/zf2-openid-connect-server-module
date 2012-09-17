<?php

namespace PhpIdServer\Context\Storage;

use PhpIdServer\General\Exception;


class StorageFactory implements \Zend\ServiceManager\FactoryInterface
{

    const CONFIG_FIELD = 'context_storage';


    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     * @return StorageInterface
     */
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
        
        $className = sprintf("%s\%s", __NAMESPACE__, $config->type);
        
        return new $className($config->options);
    }
}