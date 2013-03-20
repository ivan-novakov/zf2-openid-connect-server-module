<?php

namespace PhpIdServer\Context\Storage;


class Session extends AbstractStorage
{

    /**
     * The session container.
     *
     * @var \Zend\Session\Container
     */
    protected $_sessionContainer = NULL;

    protected $_key = 'context';


    /**
     * Sets the session container.
     *
     * @param \Zend\Session\Container $ontainer
     */
    public function setSessionContainer (\Zend\Session\Container $container)
    {
        $this->_sessionContainer = $container;
    }


    /**
     * Returns the session container.
     * 
     * @return \Zend\Session\Container
     */
    public function getSessionContainer ()
    {
        if (! ($this->_sessionContainer instanceof \Zend\Session\Container)) {
            $this->_sessionContainer = new \Zend\Session\Container($this->_options->get('session_container_name', 'authorize'));
        }
        
        return $this->_sessionContainer;
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Context\Storage\StorageInterface::save()
     */
    public function save ($context)
    {
        $this->getSessionContainer()
            ->offsetSet($this->_key, $context);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Context\Storage\StorageInterface::load()
     */
    public function load ()
    {
        if (! $this->getSessionContainer()
            ->offsetExists($this->_key)) {
            return NULL;
        }
        
        return $this->getSessionContainer()
            ->offsetGet($this->_key);
    }


    public function clear ()
    {
        $this->getSessionContainer()
            ->offsetUnset($this->_key);
    }
}