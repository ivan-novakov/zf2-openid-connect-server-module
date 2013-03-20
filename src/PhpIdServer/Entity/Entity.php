<?php

namespace PhpIdServer\Entity;

use PhpIdServer\Util\String;


/**
 * Base entity class.
 *
 */
abstract class Entity
{

    const FIELD_ID = 'id';

    /**
     * Fields definitions for the entity.
     * 
     * @var array
     */
    protected $_fields = array();

    /**
     * The identification field of the entity.
     * 
     * @var mixed
     */
    protected $_idField = self::FIELD_ID;

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
     * Returns the value of the identification field of the entity.
     * 
     * @return mixed
     */
    public function getId ()
    {
        $id = $this->getValue($this->_idField);
        /* ???
        if (NULL === $id) {
            $id = 'undefined';
        }
        */
        
        return $id;
    }


    /**
     * Magic __call method.
     * 
     * @param string $method
     * @param array $arguments
     * @throws Exception\InvalidMethodException
     * @return mixed
     */
    public function __call ($method, Array $arguments)
    {
        if (preg_match('/^get(\w+)$/', $method, $matches)) {
            $fieldName = String::camelCaseToUnderscore($matches[1]);
            return $this->getValue($fieldName);
        }
        
        if (preg_match('/^set(\w+)$/', $method, $matches)) {
            $fieldName = String::camelCaseToUnderscore($matches[1]);
            $fieldValue = null;
            if (isset($arguments[0])) {
                $fieldValue = $arguments[0];
            }
            return $this->setValue($fieldName, $fieldValue);
        }
        
        throw new Exception\InvalidMethodException($method);
    }


    /**
     * Alias for populate().
     * 
     * @param array $data
     */
    public function exchangeArray (Array $data)
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


    /**
     * Alias for toArray().
     * 
     * @return array
     */
    public function getArrayCopy ()
    {
        return $this->toArray();
    }


    /**
     * Returns the name of the entity.
     * 
     * @return string
     */
    public function getEntityName ()
    {
        $className = get_class($this);
        return String::camelCaseToUnderscore(substr($className, strrpos($className, '\\') + 1));
    }


    /**
     * Returns the string representation of the entity.
     * 
     * @return string
     */
    public function __toString ()
    {
        return sprintf("[%s #%s]", $this->getEntityName(), $this->getId());
    }


    /**
     * Checks, if the field is valid.
     * 
     * @param string $fieldName
     * @throws Exception\InvalidFieldException
     */
    protected function _checkField ($fieldName)
    {
        if (! $this->_isValidField($fieldName)) {
            throw new Exception\InvalidFieldException($fieldName, $this->getEntityName());
        }
    }


    /**
     * Returns true, if the field is valid.
     * 
     * @param string $fieldName
     * @return boolean
     */
    protected function _isValidField ($fieldName)
    {
        return (in_array($fieldName, $this->_fields));
    }
}