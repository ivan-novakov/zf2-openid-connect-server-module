<?php
namespace PhpIdServer\Context\Storage;


class SessionStorage implements StorageInterface
{

    /**
     * The session container.
     * 
     * @var \Zend\Session\Container
     */
    protected $_sessionContainer = NULL;

    protected $_key = 'context';


    /**
     * Constructor.
     * 
     * @param \Zend\Session\Container $container
     */
    public function __construct (\Zend\Session\Container $container)
    {
        $this->setSessionContainer($container);
    }


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
     * (non-PHPdoc)
     * @see \PhpIdServer\Context\Storage\StorageInterface::save()
     */
    public function save ($context)
    {
        $this->_sessionContainer->offsetSet($this->_key, $context);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Context\Storage\StorageInterface::load()
     */
    public function load ()
    {
        if (! $this->_sessionContainer->offsetExists($this->_key)) {
            return NULL;
        }
        
        return $this->_sessionContainer->offsetGet($this->_key);
    }


    public function clear ()
    {
        $this->_sessionContainer->offsetUnset($this->_key);
    }
}