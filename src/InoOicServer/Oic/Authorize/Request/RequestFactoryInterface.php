<?php

namespace InoOicServer\Oic\Authorize\Request;

use Zend\Http;


interface RequestFactoryInterface
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