<?php

namespace InoOicServer\Session\IdGenerator;

use InoOicServer\Util\Options;


abstract class AbstractIdGenerator implements IdGeneratorInterface
{

    /**
     * Generator options.
     * 
     * @var Options
     */
    protected $_options = NULL;

    /**
     * Time for the ID generation.
     * 
     * @var integer
     */
    protected $_time = NULL;

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