<?php
namespace PhpIdServer\Client\Registry\Storage;
use PhpIdServer\Client\Client;


interface StorageInterface
{


    /**
     * Returns the client object of the corresponding client ID.
     * 
     * @return Client
     */
    public function getClientById ($clientId);
}