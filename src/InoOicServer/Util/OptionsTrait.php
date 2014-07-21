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
     * @param array|\Iterator $options
     */
    public function setOptions($options)
    {
        $this->guardForArrayOrTraversable($options, 'Options');

        $options = ArrayUtils::iteratorToArray($options, true);
        $options = ArrayUtils::merge($this->getDefaultOptions(), $options);

        $this->options = $this->createOptions($options);
    }


    /**
     * @return Options
     */
    public function getOptions()
    {
        if (! $this->options instanceof Options) {
            $this->options = $this->createOptions();
        }

        return $this->options;
    }


    /**
     * @param string $optionName
     * @param mixed $defaultValue
     * @return mixed|null
     */
    public function getOption($optionName, $defaultValue = null)
    {
        return $this->getOptions()->get($optionName, $defaultValue);
    }


    /**
     * @param array $options
     * @return Options
     */
    protected function createOptions(array $options = array())
    {
        return new Options($options);
    }


    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        $defaultOptions = array();
        if (isset($this->defaultOptions) && is_array($this->defaultOptions)) {
            $defaultOptions = $this->defaultOptions;
        }

        return $defaultOptions;
    }


    /**
     * @param array $defaultOptions
     */
    public function setDefaultOptions(array $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }
}
