<?php

namespace InoOicServer\Client\Authentication\Method;

use InoOicServer\General\Exception as GeneralException;


/**
 * Factory class for creating client authentication method objects.
 */
class MethodFactory implements MethodFactoryInterface
{


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Client\Authentication\Method\MethodFactoryInterface::createAuthenticationMethod()
     */
    public function createAuthenticationMethod(array $methodConfig)
    {
        if (! isset($methodConfig['class'])) {
            throw new GeneralException\MissingParameterException('class');
        }
        
        $methodClass = $methodConfig['class'];
        if (! class_exists($methodClass)) {
            throw new GeneralException\ClassNotFoundException($methodClass);
        }
        
        $options = array();
        if (isset($methodConfig['options']) && is_array($methodConfig['options'])) {
            $options = $methodConfig['options'];
        }
        
        $method = new $methodClass($options);
        return $method;
    }
}