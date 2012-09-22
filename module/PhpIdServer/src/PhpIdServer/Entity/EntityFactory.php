<?php

namespace PhpIdServer\Entity;


class EntityFactory implements EntityFactoryInterface
{
    protected $_className = NULL;


    public function __construct ($className)
    {
        if (! class_exists($className)) {
            throw new Exception\InvalidEntityClassException($className);
        }
        
        $this->_className = $className;
    }


    public function createEntity (Array $values = array())
    {
        $className = $this->_className;
        
        return new $className($values);
    }
}