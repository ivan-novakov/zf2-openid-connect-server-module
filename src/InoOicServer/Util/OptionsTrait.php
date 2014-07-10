<?php

namespace InoOicServer\Util;

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;


trait OptionsTrait
{
    
    use ArrayOrTraversableGuardTrait;

    /**
     * @var Options
     */
    protected $options;

    /**
     * @var array
     */
    protected $defaultOptions;


    public function setOptions($options)
    {
        $this->guardForArrayOrTraversable($options, 'Options');
        
        $options = ArrayUtils::iteratorToArray($options, true);
        
        if (isset($this->defaultOptions) && is_array($this->defaultOptions)) {
            $options = ArrayUtils::merge($this->defaultOptions, $options);
        }
        
        $this->options = $this->createOptions($options);
    }


    public function getOptions()
    {
        if (! $this->options instanceof Options) {
            $this->options = $this->createOptions();
        }
        return $this->options;
    }


    public function getOption($optionName, $defaultValue = null)
    {
        return $this->getOptions()->get($optionName, $defaultValue);
    }


    protected function createOptions(array $options = array())
    {
        return new Options($options);
    }


    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->defaultOptions;
    }


    /**
     * @param array $defaultOptions
     */
    public function setDefaultOptions(array $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }
}
