<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client;


/**
 * Interface for client authentication methods.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface MethodInterface
{


    /**
     * Tries to authenticate the client. Returns a message object containing information about the
     * authentication;
     * 
     * @param Client\Authentication\Info $info
     * @return Client\Authentication\Data $data
     */
    public function authenticate(Client\Authentication\Info $info, Client\Authentication\Data $data);
}