<?php

namespace InoOicServer\Context\Storage;

use InoOicServer\Context\ContextInterface;


/**
 * The session context storage saves the context into the user session.
 */
class Session extends AbstractStorage
{

    /**
     * The session container.
     *
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;

    /**
     * @var string
     */
    protected $sessionKey = 'context';


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
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }


    /**
     * @param string $sessionKey
     */
    public function setSessionKey($sessionKey)
    {
        $this->sessionKey = $sessionKey;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Context\Storage\StorageInterface::save()
     */
    public function save(ContextInterface $context)
    {
        $this->getSessionContainer()->offsetSet($this->sessionKey, $context);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Context\Storage\StorageInterface::load()
     */
    public function load()
    {
        if (! $this->getSessionContainer()->offsetExists($this->sessionKey)) {
            return null;
        }
        
        return $this->getSessionContainer()->offsetGet($this->sessionKey);
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Context\Storage\StorageInterface::clear()
     */
    public function clear()
    {
        $this->getSessionContainer()->offsetUnset($this->sessionKey);
    }
}