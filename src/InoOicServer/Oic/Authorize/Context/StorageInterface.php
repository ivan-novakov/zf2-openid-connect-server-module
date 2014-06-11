<?php

namespace InoOicServer\Oic\Authorize\Context;


/**
 * Storage (persistence) for the context entity.
 */
interface StorageInterface
{


    /**
     * Saves the context to the storage.
     * 
     * @param Context $context
     */
    public function save(Context $context);


    /**
     * Load the context from the storage.
     * 
     * @return Authorize\Context
     */
    public function load();


    /**
     * Deletes the current context from the storage.
     */
    public function clear();
}