<?php

namespace InoOicServer\OpenIdConnect\Request\Authorize;


class RequestFactory
{


    public function createRequest(\Zend\Http\Request $httpRequest)
    {
        return new Simple($httpRequest);
    }
}