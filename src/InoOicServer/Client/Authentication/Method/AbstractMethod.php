<?php

namespace InoOicServer\Client\Authentication\Method;

use InoOicServer\Client\Authentication;
use InoOicServer\Util\Options;


/**
 * Abstract client authentication method class.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
abstract class AbstractMethod implements MethodInterface
{

    /**
     * Options.
     * @var Options
     */
    protected $options = null;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * @param array|\Traversable $options
     */
    public function setOptions($options)
    {
        $this->options = new Options($options);
    }


    /**
     * Returns the options.
     * 
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }


    /**
     * Creates and returns a successful authentication result.
     * 
     * @return Authentication\Result
     */
    public function createSuccessResult()
    {
        return new Authentication\Result(get_class($this), true);
    }


    /**
     * Creates and returns failure authentication result.
     * 
     * @param string $reason
     * @return Authentication\Result
     */
    public function createFailureResult($reason)
    {
        return new Authentication\Result(get_class($this), false, $reason);
    }
}