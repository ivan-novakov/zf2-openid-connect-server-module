<?php

namespace InoOicServer\Oic\Authorize;

use Zend\Http;


interface AuthorizeRequestFactoryInterface
{


    /**
     * Creates an authoriza request based on the provided
     * HTTP request.
     * 
     * @param Http\Request $httpRequest
     * @return Request
     */
    public function createRequest(Http\Request $httpRequest);
}