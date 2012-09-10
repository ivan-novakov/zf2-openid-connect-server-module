<?php
namespace PhpIdServer\Authentication;
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


    public function getAuthenticationRouteName ()
    {
        return $this->_options->get('handler_endpoint_route');
    }
}