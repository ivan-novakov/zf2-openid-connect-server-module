<?php

namespace PhpIdServer\Authentication;

use Zend\Mvc\Router\RouteStackInterface;
use PhpIdServer\Util\Options;


class Manager
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = NULL;


    /**
     * Constructor.
     * 
     * @param array|Traversable $options
     */
    public function __construct ($options)
    {
        $this->_options = new Options($options);
    }


    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    public function getAuthenticationRouteName ()
    {
        return $this->getOption('base_route');
    }
}