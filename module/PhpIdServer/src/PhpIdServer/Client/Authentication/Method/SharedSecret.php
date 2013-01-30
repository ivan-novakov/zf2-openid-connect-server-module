<?php

namespace PhpIdServer\Client\Authentication\Method;

use PhpIdServer\Client;


class SharedSecret extends AbstractMethod
{


    public function authenticate(Client\Authentication\Info $info, Client\Authentication\Data $data)
    {
        $authData = $request->getAuthenticationData();
    }
}