<?php

namespace PhpIdServer\Session\IdGenerator;

use PhpIdServer\General\Exception;


class IdGeneratorFactory implements \Zend\ServiceManager\FactoryInterface
{

    const CONFIG_FIELD = 'session_id_generator';


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