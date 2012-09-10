<?php

namespace PhpIdServer\OpenIdConnect;


class RequestFactory
{


    static public function factory (\Zend\Http\Request $httpRequest)
    {
        return new Request\Simple($httpRequest);
    }
}