<?php

namespace InoOicServer\Client\Authentication\Method;

use InoOicServer\Client;
use Zend\Http;


/**
 * Interface for client authentication methods.
 */
interface MethodInterface
{


    /**
     * Tries to authenticate the client. Returns a message object containing information about the
     * authentication;
     * 
     * @param Client\Authentication\Info $info
     * @param Http\Request $httpRequest
     * @return Client\Authentication\Result
     */
    public function authenticate(Client\Authentication\Info $info, Http\Request $httpRequest);
}