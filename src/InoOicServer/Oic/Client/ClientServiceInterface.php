<?php

namespace InoOicServer\Oic\Client;


interface ClientServiceInterface
{


    /**
     * Fetches the client with the required client ID.
     * 
     * @param string $clientId
     * @param string $redirectUri
     * @return \InoOicServer\Oic\Client\Client
     */
    public function fetchClient($clientId, $redirectUri = null);
}