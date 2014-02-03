<?php

namespace InoOicServer\Context\Storage;

use InoOicServer\Context\ContextInterface;


/**
 * Interface for context storage.
 */
interface StorageInterface
{


    /**
     * Loads the context from the srtorage.
     * 
     * @return ContextInterface
     */
    public function load();


    /**
     * Saves a context to the storage.
     * 
     * @param ContextInterface $context
     */
    public function save(ContextInterface $context);


    /**
     * Clears the context from the storage.
     */
    public function clear();
}