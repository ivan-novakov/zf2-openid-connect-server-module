<?php

namespace PhpIdServer\General;


interface OptionContainerInterface
{


    /**
     * Sets all options at once.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions ($options);


    /**
     * Returns all options as array.
     * 
     * @return array
     */
    public function getOptions ();


    /**
     * Sets a particular option.
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setOption ($name, $value);


    /**
     * Returns a particular option.
     * 
     * @param string $name
     */
    public function getOption ($name);
}