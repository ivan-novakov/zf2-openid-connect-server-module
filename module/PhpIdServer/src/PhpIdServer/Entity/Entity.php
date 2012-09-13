<?php

namespace PhpIdServer\Entity;

use PhpIdServer\Util\String;


abstract class Entity
{

    protected $_fields = array();

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


    public function __call ($method, $arguments)
    {
        if (preg_match('/^get(\w+)$/', $method, $matches)) {
            $fieldName = String::camelCaseToUnderscore($matches[1]);
            return $this->getValue($fieldName);
        }
        
        throw new Exception\InvalidMethodException($method);
    }


    public function exchangeArray (Array $data)
    {
        return $this->populate($data);
    }


    /**
     * Populates the entity with data.
     * 
     * @param array $data
     */
    public function populate (Array $data)
    {
        $this->_data = new \ArrayObject(array());
        
        foreach ($data as $fieldName => $fieldValue) {
            $setter = 'set' . String::underscoreToCamelCase($fieldName);
            
            if (method_exists($this, $setter)) {
                call_user_func_array(array(
                    $this, 
                    $setter
                ), array(
                    $fieldValue
                ));
            } else {
                $this->setValue($fieldName, $fieldValue);
            }
        }
    }


    /**
     * Returns a value with the provided index or NULL if there is no such index.
     *
     * @param string $ey
     * @return mixed|NULL
     */
    public function getValue ($fieldName)
    {
        $this->_checkField($fieldName);
        
        if ($this->_data->offsetExists($fieldName)) {
            return $this->_data->offsetGet($fieldName);
        }
        
        return NULL;
    }


    /**
     * Sets the value for the supplied index.
     * 
     * @param string $key
     * @param mixed $value
     */
    public function setValue ($fieldName, $fieldValue)
    {
        $this->_checkField($fieldName);
        $this->_data->offsetSet($fieldName, $fieldValue);
    }


    /**
     * Returns entity data as an array.
     * 
     * @return array
     */
    public function toArray ()
    {
        $arrayData = array();
        foreach ($this->_data as $fieldName => $fieldValue) {
            $arrayData[$fieldName] = $fieldValue;
        }
        
        return $arrayData;
    }


    public function getArrayCopy ()
    {
        return $this->toArray();
    }


    public function getEntityName ()
    {
        return get_class($this);
    }


    protected function _checkField ($fieldName)
    {
        if (! $this->_isValidField($fieldName)) {
            throw new Exception\InvalidFieldException($fieldName, $this->getEntityName());
        }
    }


    protected function _isValidField ($fieldName)
    {
        return (in_array($fieldName, $this->_fields));
    }
}