<?php

namespace InoOicServer\Util;


class Options extends \ArrayObject
{


    /**
     * Returns the option for the corresponding key.
     * 
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed|null
     */
    public function get($key, $defaultValue = null)
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }
        
        if (null !== $defaultValue) {
            return $defaultValue;
        }
        
        return null;
    }


    /**
     * Sets the value to with the corresponding key.
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);
    }
}