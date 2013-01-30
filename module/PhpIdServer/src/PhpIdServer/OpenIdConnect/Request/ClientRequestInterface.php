<?php

namespace PhpIdServer\OpenIdConnect\Request;

use PhpIdServer\Client;


/**
 * Interface for requests performed by a client application.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface ClientRequestInterface
{


    /**
     * Returns an object containing parsed data from the "Authorization" HTTP header.
     * 
     * @return Client\Authentication\Data
     */
    public function getAuthenticationData();
}