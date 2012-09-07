<?php
namespace PhpIdServer\Client\Authentication;
use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect;


/**
 * The authentication manager authenticates the client using the client request data and the client info from the
 * local registry.
 *
 */
class Manager
{


    public function authenticate (OpenIdConnect\Request $request, Client $client)
    {}
}