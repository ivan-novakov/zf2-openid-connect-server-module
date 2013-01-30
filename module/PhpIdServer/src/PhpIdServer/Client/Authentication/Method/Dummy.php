<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect\Request\ClientRequestInterface;


/**
 * Dummy client authentication method simulating successfull authentication.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Dummy extends AbstractMethod
{

    const OPT_SUCCESS = 'success';


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Client\Authentication\Method\MethodInterface::authenticate()
     */
    public function authenticate(ClientRequestInterface $request, Client $client)
    {
        if ($this->_options->get(self::OPT_SUCCESS, false)) {
            return $this->createSuccessResult();
        }
        
        return $this->createFailureResult('dummy failure reason');
    }
}