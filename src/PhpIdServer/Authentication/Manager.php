<?php

namespace PhpIdServer\Authentication;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\Util\Options;
use PhpIdServer\Context;


class Manager
{

    const OPT_BASE_ROUTE = 'base_route';

    const OPT_DEFAULT_AUTHENTICATION_HANDLER = 'default_authentication_handler';

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = NULL;

    /**
     * Context object
     * @var Context\AuthorizeContext
     */
    protected $_context = NULL;


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
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * Returns the value of the required option.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed|null
     */
    public function getOption($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    /**
     * Sets the context object.
     * 
     * @param Context\AuthorizeContext $context
     */
    public function setContext(Context\AuthorizeContext $context)
    {
        $this->_context = $context;
    }


    /**
     * Returns the context object.
     * 
     * @param boolean $throwException
     * @throws GeneralException\MissingDependencyException
     * @return Context\AuthorizeContext
     */
    public function getContext($throwException = false)
    {
        if ($throwException && ! ($this->_context instanceof Context\AuthorizeContext)) {
            throw new GeneralException\MissingDependencyException('context', $this);
        }
        
        return $this->_context;
    }


    /**
     * Returns the base route identifier.
     * 
     * @return string
     */
    public function getAuthenticationRouteName()
    {
        return $this->getOption(self::OPT_BASE_ROUTE);
    }


    /**
     * Returns the default authentication handler name.
     * 
     * @throws \RuntimeException
     * @return string
     */
    public function getAuthenticationHandler()
    {
        $client = $this->getContext(true)->getClient();
        
        if (! $client) {
            throw new \RuntimeException('Client object not found in context');
        }
        
        $handler = $client->getUserAuthenticationHandler();
        if (! $handler) {
            $handler = $this->getOption(self::OPT_DEFAULT_AUTHENTICATION_HANDLER);
        }
        
        if (! $handler) {
            throw new \RuntimeException('No default authentication handler specified');
        }
        
        // FIXME - validate handler
        
        return $handler;
    }
}