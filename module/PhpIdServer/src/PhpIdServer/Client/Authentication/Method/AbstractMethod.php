<?php
namespace PhpIdServer\Client\Authentication\Method;
use PhpIdServer\Util\Options;


class AbstractMethod implements MethodInterface
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Client\Authentication\Method\MethodInterface::setOptions()
     */
    public function setOptions ($options)
    {
        $this->_options = new Options($options);
    }
}