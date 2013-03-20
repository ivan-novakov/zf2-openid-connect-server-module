<?php

namespace PhpIdServer\General;

use PhpIdServer\Util\Options;


abstract class Component implements OptionContainerInterface
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = null;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\General\OptionContainerInterface::setOptions()
     */
    public function setOptions ($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\General\OptionContainerInterface::getOptions()
     */
    public function getOptions ()
    {
        return $this->_options->getArrayCopy();
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\General\OptionContainerInterface::setOption()
     */
    public function setOption ($name, $value)
    {
        $this->_options->set($name, $value);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\General\OptionContainerInterface::getOption()
     */
    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }
}