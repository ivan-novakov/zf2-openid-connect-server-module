<?php

namespace InoOicServer\Oic\Authorize\Context;

use InoOicServer\Oic\Authorize;


/**
 * Storage (persistence) for the context entity.
 */
interface StorageInterface
{


    /**
     * Saves the context to the storage.
     * 
     * @param Authorize\Context $context
     */
    public function save(Authorize\Context $context);


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