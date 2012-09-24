<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client;


interface MethodInterface
{


    /**
     * Sets the method options.
     * 
     * @param array|Traversable $options
     */
    public function setOptions ($options);


    /**
     * Tries to authenticate the client. Returns a message object containing information about the
     * authentication;
     * 
     * @param Client\Client $client
     * @return Client\Authentication\Message
     */
    public function authenticate (Client\Client $client);
}