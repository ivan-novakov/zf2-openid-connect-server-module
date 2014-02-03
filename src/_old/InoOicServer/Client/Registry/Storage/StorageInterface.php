<?php
namespace InoOicServer\Client\Registry\Storage;
use InoOicServer\Client\Client;


interface StorageInterface
{


    /**
     * Returns the client object of the corresponding client ID.
     * 
     * @return Client
     */
    public function getClientById ($clientId);
}