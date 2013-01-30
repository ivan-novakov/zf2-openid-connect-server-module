<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client;


class SharedSecret extends AbstractMethod
{


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Client\Authentication\Method\MethodInterface::authenticate()
     */
    public function authenticate(Client\Authentication\Info $info, Client\Authentication\Data $data)
    {
        $authData = $request->getAuthenticationData();
    }
}