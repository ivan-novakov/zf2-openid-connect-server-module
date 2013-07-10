<?php

namespace PhpIdServer\Context\Storage;


class Session extends AbstractStorage
{

    /**
     * The session container.
     *
     * @var \Zend\Session\Container
     */
    protected $sessionContainer = NULL;

    protected $key = 'context';


    /**
     * Sets the session container.
     *
     * @param \Zend\Session\Container $ontainer
     */
    public function setSessionContainer(\Zend\Session\Container $container)
    {
        $this->sessionContainer = $container;
    }


    /**
     * Returns the session container.
     * 
     * @return \Zend\Session\Container
     */
    public function getSessionContainer()
    {
        if (! ($this->sessionContainer instanceof \Zend\Session\Container)) {
            $this->sessionContainer = new \Zend\Session\Container($this->_options->get('session_container_name', 'authorize'));
        }
        
        return $this->sessionContainer;
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Context\Storage\StorageInterface::save()
     */
    public function save($context)
    {
        $this->getSessionContainer()->offsetSet($this->key, $context);
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Context\Storage\StorageInterface::load()
     */
    public function load()
    {
        if (! $this->getSessionContainer()->offsetExists($this->key)) {
            return NULL;
        }
        
        return $this->getSessionContainer()->offsetGet($this->key);
    }


    public function clear()
    {
        $this->getSessionContainer()->offsetUnset($this->key);
    }
}