<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect\Request\ClientRequestInterface;


interface MethodInterface
{


    /**
     * Tries to authenticate the client. Returns a message object containing information about the
     * authentication;
     * 
     * @param Client\Client $client
     * @return Client\Authentication\Result
     */
    public function authenticate(ClientRequestInterface $request, Client $client);
}