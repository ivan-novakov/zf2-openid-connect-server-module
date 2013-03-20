<?php

namespace PhpIdServer\Client\Authentication;


/**
 * Simple class that contains information about the authentication type and parameters of the client.
 *
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Info
{

    /**
     * Authentication type.
     * 
     * @var string
     */
    protected $_method = NULL;

    /**
     * Authentication options.
     * 
     * @var array
     */
    protected $_options = array();


    /**
     * Contructor.
     * 
     * @param string $method
     * @param array $options
     */
    public function __construct($method, Array $options = array())
    {
        $this->setMethod($method);
        $this->setOptions($options);
    }


    /**
     * Sets the authentication type.
     * 
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->_method = $method;
    }


    /**
     * Returns the authentication type.
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }


    /**
     * Sets the authentication options.
     * 
     * @param array $options
     */
    public function setOptions(Array $options)
    {
        $this->_options = $options;
    }


    /**
     * Returns the authentication options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }


    /**
     * Returns a specific option value.
     * 
     * @param string $name
     * @return mixed|null
     */
    public function getOption($name)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        }
        
        return null;
    }
}