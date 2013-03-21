<?php

namespace PhpIdServer\Authentication;

use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory;


/**
 * The class validates and filters raw user attribute data. 
 * 
 * It encapsulates the ZF2 InputFilter class:
 * https://packages.zendframework.com/docs/latest/manual/en/index.html#zend-inputfilter
 * 
 * @see https://packages.zendframework.com/docs/latest/manual/en/index.html#zend-inputfilter
 */
class AttributeFilter
{

    /**
     * The internal ZF input filter object.
     * 
     * @var InputFilterInterface
     */
    protected $inputFilter = null;


    /**
     * Constructor.
     * 
     * @param \Traversable|array $config
     */
    public function __construct(array $config, Factory $inputFilterFactory = null)
    {
        if (null === $inputFilterFactory) {
            $inputFilterFactory = new Factory();
        }
        
        try {
            $this->inputFilter = $inputFilterFactory->createInputFilter($config);
        } catch (\Exception $e) {
            throw new Exception\CreateInputFilterException(sprintf("Error creating ZF2 InputFilter: [%s] %s", get_class($e), $e->getMessage()));
        }
    }


    /**
     * Validates the attributes according to the configuration and throws an exception in case of invalid input.
     * 
     * @param array $attributes
     * @throws Exception\InvalidInputException
     */
    public function filterValues(array $attributes)
    {
        $this->inputFilter->setData($attributes);
        if (! $this->inputFilter->isValid()) {
            throw new Exception\InvalidInputException($this->inputFilter->getMessages());
        }
        
        return $this->inputFilter->getValues();
    }
}