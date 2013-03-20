<?php

namespace PhpIdServer\Client\Authentication;


/**
 * Authentication data used to authenticate a client.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Data
{

    /**
     * Authentication method.
     * 
     * @var string
     */
    protected $_method = null;

    /**
     * Authentication parameters.
     * 
     * @var array
     */
    protected $_params = array();


    /**
     * Constructor.
     * 
     * @param string $method
     * @param array $params
     */
    public function __construct($method, array $params = array())
    {
        $this->_method = $method;
        $this->_params = $params;
    }


    /**
     * Returns the authentication method.
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }


    /**
     * Returns all authentication parameters.
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * Returns a specific parameter.
     * 
     * @param string $paramName
     * @return mixed|null
     */
    public function getParam($paramName)
    {
        if (isset($this->_params[$paramName])) {
            return $this->_params[$paramName];
        }
        
        return null;
    }
}