<?php

namespace PhpIdServer\User\DataConnector;

use PhpIdServer\General\Exception as GeneralException;


class DataConnectorFactory
{


    /**
     * Creates and returns a data connector.
     * 
     * @param array $config
     * @throws GeneralException\MissingConfigException
     * @return DataConnectorInterface
     */
    public function createDataConnector (array $config)
    {
        if (! isset($config['class'])) {
            throw new GeneralException\MissingConfigException('class');
        }
        
        $className = $config['class'];
        if (! class_exists($className)) {
            throw new GeneralException\InvalidClassException(sprintf("The class '%s' does not exist", $className));
        }
        
        $options = array();
        if (isset($config['options']) && is_array($config['options'])) {
            $options = $config['options'];
        }
        
        $dataConnector = new $className($options);
        if (! ($dataConnector instanceof DataConnectorInterface)) {
            throw new GeneralException\InvalidClassException(sprintf("The class '%s' does not implement the 'DataConnectorInterfaces' interface", $className));
        }
        
        return $dataConnector;
    }
}