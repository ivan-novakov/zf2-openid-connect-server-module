<?php

namespace InoOicServer\Oic\Authorize\Http;

use Zend\Http;
use InoOicServer\Oic\Authorize\Result;
use InoOicServer\Oic\Authorize\AuthorizeRequest;


interface HttpServiceInterface
{


    /**
     * Creates an authorize request entity based on the HTTP request.
     * 
     * @param Http\Request $request
     * @return AuthorizeRequest
     */
    public function createAuthorizeRequest(Http\Request $httpRequest);


    /**
     * Creates a HTTP response based on an authorize result.
     * 
     * @param Result $result
     * @return Http\Response
     */
    public function createHttpResponse(Result $result);
}