<?php

namespace InoOicServer\Oic\Authorize\Context;

use Zend\Session;
use InoOicServer\Oic\Authorize;


/**
 * The storage uses the current browser session to save data.
 */
class SessionStorage implements StorageInterface
{

    /**
     * @var Session\Container
     */
    protected $sessionContainer;

    /**
     * @var string
     */
    protected $sessionIndex = 'context';


    /**
     * Constructor.
     * 
     * @param Session\Container $sessionContainer
     */
    public function __construct(Session\Container $sessionContainer, $sessionIndex = null)
    {
        $this->setSessionContainer($sessionContainer);
        
        if (null !== $sessionIndex) {
            $this->setSessionIndex($sessionIndex);
        }
    }


    /**
     * @return Session\Container
     */
    public function getSessionContainer()
    {
        return $this->sessionContainer;
    }


    /**
     * @param Session\Container $sessionContainer
     */
    public function setSessionContainer(Session\Container $sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }


    /**
     * @return string
     */
    public function getSessionIndex()
    {
        return $this->sessionIndex;
    }


    /**
     * @param string $sessionIndex
     */
    public function setSessionIndex($sessionIndex)
    {
        $this->sessionIndex = $sessionIndex;
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Context\StorageInterface::save()
     */
    public function save(Authorize\Context $context)
    {
        $this->getSessionContainer()->offsetSet($this->sessionIndex, $context);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Context\StorageInterface::load()
     */
    public function load()
    {
        if (! $this->getSessionContainer()->offsetExists($this->sessionIndex)) {
            return null;
        }
        
        return $this->getSessionContainer()->offsetGet($this->sessionIndex);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Context\StorageInterface::clear()
     */
    public function clear()
    {
        $this->getSessionContainer()->offsetUnset($this->sessionIndex);
    }
}