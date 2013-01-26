<?php
namespace PhpIdServer\Client\Authentication;


/**
 * Simple class that contains information about the authentication type and parameters of the client.
 *
 */
class Info
{

    /**
     * Authentication type.
     * 
     * @var string
     */
    protected $_type = NULL;

    /**
     * Authentication options.
     * 
     * @var array
     */
    protected $_options = array();


    /**
     * Contructor.
     * 
     * @param string $type
     * @param array $options
     */
    public function __construct ($type, Array $options = array())
    {
        $this->setType($type);
        $this->setOptions($options);
    }


    /**
     * Sets the authentication type.
     * 
     * @param string $type
     */
    public function setType ($type)
    {
        $this->_type = $type;
    }


    /**
     * Returns the authentication type.
     * 
     * @return string
     */
    public function getType ()
    {
        return $this->_type;
    }


    /**
     * Sets the authentication options.
     * 
     * @param array $options
     */
    public function setOptions (Array $options)
    {
        $this->_options = $options;
    }


    /**
     * Returns the authentication options.
     * 
     * @return array
     */
    public function getOptions ()
    {
        return $this->_options;
    }
}