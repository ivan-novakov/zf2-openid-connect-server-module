<?php

namespace InoOicServer\Client\Authentication\Method;

use InoOicServer\Client;
use Zend\Http;


class SecretBasic extends AbstractMethod
{


    public function authenticate(Client\Authentication\Info $info, Http\Request $httpRequest)
    {}
}