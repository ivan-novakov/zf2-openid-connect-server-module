<?php

namespace PhpIdServer\Context\Storage;

use PhpIdServer\Util\Options;


abstract class AbstractStorage implements StorageInterface
{

    /**
     * Storage options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    /**
     * Constructor.
     * 
     * @param array|Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->_options = new Options($options);
    }
}