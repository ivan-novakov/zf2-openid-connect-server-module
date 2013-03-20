<?php

namespace PhpIdServer\Session\Hash\Generator;

use PhpIdServer\Util\Options;


abstract class AbstractGenerator implements GeneratorInterface
{

    /**
     * Generator options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->_options = new Options($options);
    }
}