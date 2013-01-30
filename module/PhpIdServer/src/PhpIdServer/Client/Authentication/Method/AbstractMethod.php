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
        return new Authentication\Result(false, $reason);
    }
}