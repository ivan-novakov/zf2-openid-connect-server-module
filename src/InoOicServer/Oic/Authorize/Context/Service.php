<?php

namespace InoOicServer\Oic\Authorize\Context;

use InoOicServer\Oic\Authorize;


/**
 * Authorize context service.
 */
class Service
{

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var ContextFactoryInterface
     */
    protected $factory;


    /**
     * Constructor.
     * 
     * @param StorageInterface $storage
     * @param ContextFactoryInterface $factory$context
     */
    public function __construct(StorageInterface $storage, ContextFactoryInterface $factory = null)
    {
        $this->setStorage($storage);
        
        if (null === $factory) {
            $factory = new ContextFactory();
        }
        $this->setFactory($factory);
    }


    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }


    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }


    /**
     * @return ContextFactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }


    /**
     * @param ContextFactoryInterface $factory
     */
    public function setFactory(ContextFactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    /**
     * Creates a new context, saves it and returns it.
     * 
     * @return Authorize\Context
     */
    public function createContext()
    {
        $context = $this->getFactory()->createContext();
        $this->saveContext($context);
        
        return $context;
    }


    /**
     * Saves the provided authorize context.
     * 
     * @param Authorize\Context $context
     */
    public function saveContext(Authorize\Context $context)
    {
        $this->getStorage()->save($context);
    }


    /**
     * Loads a previously saved authorize context.
     *
     * @return Authorize\Context
     */
    public function loadContext()
    {
        return $this->getStorage()->load();
    }


    /**
     * Returns true, if there exists a valid context saved.
     * 
     * @return boolean
     */
    public function existsValidContext()
    {
        return ($this->loadContext() !== null);
    }


    /**
     * Clears the currently saved context.
     */
    public function clearContext()
    {
        $this->getStorage()->clear();
    }
}