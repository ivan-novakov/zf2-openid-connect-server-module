<?php

namespace InoOicServer\Session\Hash\Generator;

use InoOicServer\Util\Options;


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