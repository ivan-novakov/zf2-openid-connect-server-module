<?php

namespace InoOicServer\Oic\Authorize\Response;

use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Authorize\AuthorizeRequest;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Error;


interface ResponseFactoryInterface
{


    /**
     * @param AuthCode $authCode
     * @param AuthorizeRequest $request
     * @param Session $session
     * @return AuthorizeResponse
     */
    public function createAuthorizeResponse(AuthCode $authCode, AuthorizeRequest $request, Session $session);


    /**
     * @param Error $error
     * @param AuthorizeRequest $request
     * @return AuthorizeErrorResponse
     */
    public function createAuthorizeErrorResponse(Error $error, AuthorizeRequest $request);


    /**
     * @param Error $error
     * @return ClientErrorResponse
     */
    public function createClientErrorResponse(Error $error);
}