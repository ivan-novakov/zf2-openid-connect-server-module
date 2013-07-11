<?php

namespace InoOicServer\Client\Authentication;


/**
 * Simple class that contains information about the authentication type and parameters of the client.
 */
class Info
{

    /**
     * @var string
     */
    protected $clientId;

    /**
     * Authentication type.
     * 
     * @var string
     */
    protected $method = NULL;

    /**
     * Authentication options.
     * 
     * @var array
     */
    protected $options = array();


    /**
     * Contructor.
     * 
     * @param string $method
     * @param array $options
     */
    public function __construct($clientId, $method, Array $options = array())
    {
        $this->setClientId($clientId);
        $this->setMethod($method);
        $this->setOptions($options);
    }


    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }


    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }


    /**
     * Sets the authentication type.
     * 
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }


    /**
     * Returns the authentication type.
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * Sets the authentication options.
     * 
     * @param array $options
     */
    public function setOptions(Array $options)
    {
        $this->options = $options;
    }


    /**
     * Returns the authentication options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Returns a specific option value.
     * 
     * @param string $name
     * @return mixed|null
     */
    public function getOption($name)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        
        return null;
    }
}