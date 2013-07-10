<?php

namespace PhpIdServer\Context;


class AbstractContext
{

    /**
     * Saved context data.
     * 
     * @var \ArrayObject
     */
    protected $contextData = NULL;


    /**
     * Sets context information.
     * 
     * @param string $label
     * @param mixed $value
     */
    public function setValue ($label, $value)
    {
        $this->getContextData()
            ->offsetSet($label, $value);
    }


    /**
     * Gets context information.
     * 
     * @param string $label
     * @return mixed|NULL
     */
    public function getValue ($label)
    {
        $data = $this->getContextData();
        
        if ($data->offsetExists($label)) {
            return $data->offsetGet($label);
        }
        
        return NULL;
    }


    /**
     * Returns the context data storage object.
     * 
     * @return \ArrayObject
     */
    public function getContextData ()
    {
        if (! ($this->contextData instanceof \ArrayObject)) {
            $this->contextData = new \ArrayObject(array());
        }
        
        return $this->contextData;
    }
}