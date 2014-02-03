<?php

namespace InoOicServer\User\Validator;

use InoOicServer\General\Exception as GeneralException;


/**
 * A generic factory class for creating user validators.
 */
class ValidatorFactory
{


    /**
     * Creates a user validator based on the provided configuration.
     * The config array expects these fields:
     *   - 'class' (required) - string, the class name of the validator
     *   - 'options' (optional) - array of options to be passed to the valdiator
     * 
     * @param array $config
     * @throws GeneralException\MissingConfigException
     * @throws GeneralException\InvalidClassException
     * @return ValidatorInterface
     */
    public function createValidator(array $config)
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
        
        $validator = new $className($options);
        if (! $validator instanceof ValidatorInterface) {
            throw new GeneralException\InvalidClassException(sprintf("The class '%s' does not implement the 'ValidatorInterface' interface", $className));
        }
        
        return $validator;
    }
}