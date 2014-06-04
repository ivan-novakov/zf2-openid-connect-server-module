<?php

namespace InoOicServer\Oic\Client;

use InoOicServer\Oic\Token\TokenRequest;


interface ClientServiceInterface
{


    /**
     * Resolves, validates and authenticates a client based on its request.
     * 
     * @param Http\Request $httpRequest
     * @return Client
     */
    public function resolveClientByTokenRequest(TokenRequest $request);


    /**
     * Fetches the client with the required client ID.
     * 
     * @param string $clientId
     * @param string $redirectUri
     * @return Client
     */
    public function fetchClient($clientId, $redirectUri = null);
}