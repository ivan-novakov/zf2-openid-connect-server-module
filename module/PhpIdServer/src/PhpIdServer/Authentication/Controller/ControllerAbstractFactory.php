<?php

namespace PhpIdServer\Authentication\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use PhpIdServer\ServiceManager\Exception as ServiceManagerException;


class ControllerAbstractFactory implements AbstractFactoryInterface
{


    public function canCreateServiceWithName (ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (null !== $this->_getControllerConfig($serviceLocator, $requestedName));
    }


    public function createServiceWithName (ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $controllerConfig = $this->_getControllerConfig($serviceLocator, $requestedName);
        if (! $controllerConfig) {
            throw new ServiceManagerException\ConfigNotFoundException("authentication_handlers/$requestedName");
        }
        
        if (! isset($controllerConfig['class'])) {
            throw new ServiceManagerException\ConfigNotFoundException("authentication_handlers/$requestedName/class");
        }
        
        $className = $controllerConfig['class'];
        if (! class_exists($className)) {
            throw new ServiceManagerException\ClassNotFoundException($className);
        }
        
        $controller = new $className();
        
        $options = array(
            'label' => $requestedName
        );
        
        if (isset($controllerConfig['options']) && is_array($controllerConfig['options'])) {
            $options = $controllerConfig['options'] + $options;
        }
        
        $controller->setOptions($options);
        
        return $controller;
    }


    protected function _getControllerConfig (ServiceLocatorInterface $serviceLocator, $requestedName)
    {
        $config = $this->_getServiceManager($serviceLocator)
            ->get('Config');
        
        if (! isset($config['authentication_handlers']) || ! is_array($config['authentication_handlers'])) {
            throw new ServiceManagerException\ConfigNotFoundException('authentication_handlers');
        }
        
        $authConfig = $config['authentication_handlers'];
        
        if (! isset($authConfig[$requestedName]) || ! is_array($authConfig[$requestedName])) {
            return null;
        }
        
        return $authConfig[$requestedName];
    }


    protected function _getServiceManager (ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->getServiceLocator();
    }
}