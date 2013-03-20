<?php

namespace PhpIdServer\Client\Registry;

use PhpIdServer\Client\Client;


class Registry
{

    /**
     * The storage object.
     * 
     * @var Storage\StorageInterface
     */
    protected $_storage = NULL;


    /**
     * Constructor.
     * 
     * @param Storage\StorageInterface $storage
     */
    public function __construct (Storage\StorageInterface $storage)
    {
        $this->setStorage($storage);
    }


    /**
     * Sets the registry storage.
     * 
     * @param Storage\StorageInterface $storage
     */
    public function setStorage (Storage\StorageInterface $storage)
    {
        $this->_storage = $storage;
    }


    /**
     * Returns the registry storage.
     * 
     * @return Storage\StorageInterface
     */
    public function getStorage ()
    {
        return $this->_storage;
    }


    /**
     * Returns the client with the specified ID.
     * 
     * @param string $clientId
     * @return Client
     */
    public function getClientById ($clientId, $throwException = false)
    {
        $client = $this->_storage->getClientById($clientId);
        if (! ($client instanceof Client)) {
            if ($throwException) {
                throw new Exception\ClientNotFoundException($clientId);
            }
            
            return NULL;
        }
        
        return $client;
    }
}