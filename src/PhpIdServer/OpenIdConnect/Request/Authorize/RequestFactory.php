<?php

namespace PhpIdServer\OpenIdConnect\Request\Authorize;


class RequestFactory
{


    static public function factory (\Zend\Http\Request $httpRequest)
    {
        return new Simple($httpRequest);
    }
}