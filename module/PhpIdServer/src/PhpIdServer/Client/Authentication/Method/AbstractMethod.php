<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client\Authentication;
use PhpIdServer\Util\Options;


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
    protected $_options = null;


    public function __construct($options = array())
    {
        $this->setOptions($options);
    }


    public function setOptions($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * Creates and returns a successful authentication result.
     * 
     * @return Authentication\Result
     */
    public function createSuccessResult()
    {
        return new Authentication\Result(true);
    }


    /**
     * Creates and returns failure authentication result.
     * 
     * @param string $reason
     * @return Authentication\Result
     */
    public function createFailureResult($reason)
    {
        return new Authentication\Result(false, sprintf("[%s] %s", get_class($this), $reason));
    }
}