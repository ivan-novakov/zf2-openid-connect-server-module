<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\General\Exception as GeneralException;


/**
 * Factory class for creating client authentication method objects.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class MethodFactory
{

    /**
     * Available authentication methods info. 
     * Each method info is referenced by a method name. The info contains the name of the class
     * and optionally an array of options.
     * Example:
     * 
     * <code>
     * $methods = array(
     *     'dummy' => array(
     *         'class' => 'My\Method\Dummy',
     *         'options' => array()
     *     );
     * );
     * </code>
     * 
     * @var array
     */
    protected $methods = array();


    /**
     * Constructor.
     * 
     * @param array $methods
     */
    public function __construct(array $methods)
    {
        $this->methods = $methods;
    }


    /**
     * Creates and returns an authentication method object according to the provided method name.
     * 
     * @param string $methodName
     * @throws Exception\InvalidAuthenticationMethodException
     * @throws GeneralException\MissingParameterException
     * @throws GeneralException\InvalidClassException
     * @return MethodInterface
     */
    public function createMethod($methodName)
    {
        $methodInfo = $this->getMethodInfo($methodName);
        if (null === $methodInfo) {
            throw new Exception\InvalidAuthenticationMethodException($methodName);
        }
        
        if (! isset($methodInfo['class'])) {
            throw new GeneralException\MissingParameterException('class');
        }
        
        $methodClass = $methodInfo['class'];
        if (! class_exists($methodClass)) {
            throw new GeneralException\ClassNotFoundException($methodClass);
        }
 
        $options = array();
        if (isset($methodInfo['options']) && is_array($methodInfo['options'])) {
            $options = $methodInfo['options'];
        }
        
        $method = new $methodClass($options);
        
        return $method;
    }


    /**
     * Returns a method info array for the provided method name.
     * 
     * @param string $methodName
     * @return array|null
     */
    public function getMethodInfo($methodName)
    {
        if (! isset($this->methods[$methodName]) || ! is_array($this->methods[$methodName])) {
            return null;
        }
        
        return $this->methods[$methodName];
    }
}