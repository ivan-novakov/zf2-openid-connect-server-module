<?php

namespace PhpIdServer\Entity;


class Entity
{

    /**
     * Entity data.
     * 
     * @var \ArrayObject
     */
    protected $_data = NULL;


    /**
     * Constructor.
     * 
     * @param array $data
     */
    public function __construct (Array $data = array())
    {
        $this->populate($data);
    }


    /**
     * Populates the entity with data.
     * 
     * @param array $data
     */
    public function populate (Array $data)
    {
        $this->_data = new \ArrayObject($data);
    }


    /**
     * Returns a value with the provided index or NULL if there is no such index.
     *
     * @param string $ey
     * @return mixed|NULL
     */
    public function getValue ($key)
    {
        if ($this->_data->offsetExists($key)) {
            return $this->_data->offsetGet($key);
        }
        
        return NULL;
    }


    /**
     * Sets the value for the supplied index.
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setValue ($key, $value)
    {
        $this->_data->offsetSet($key, $value);
    }


    /**
     * Returns entity data as an array.
     * 
     * @return array
     */
    public function toArray ()
    {
        return (array) $this->_data;
    }
}