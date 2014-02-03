<?php

namespace InoOicServer\Context;


interface ContextInterface
{


    /**
     * Sets a context value.
     * 
     * @param string $label
     * @param mixed $value
     */
    public function setValue($label, $value);


    /**
     * Gets context value. Returns null, if the value is missing.
     *
     * @param string $label
     * @return mixed|null
     */
    public function getValue($label);
}