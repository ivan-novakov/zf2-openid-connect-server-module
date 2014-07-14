<?php

namespace InoOicServer\Oic\Authorize\Response;

use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Authorize\AuthorizeRequest;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Error;


class ResponseFactory implements ResponseFactoryInterface
{


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Response\ResponseFactoryInterface::createAuthorizeResponse()
     */
    public function createAuthorizeResponse(AuthCode $authCode, AuthorizeRequest $request, Session $session)
    {
        $response = new AuthorizeResponse();
        $response->setRedirectUri($request->getRedirectUri());
        $response->setState($request->getState());
        $response->setCode($authCode->getCode());
        $response->setAuthSessionId($session->getAuthSessionId());
        $response->setSessionId($session->getId());
        
        return $response;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Response\ResponseFactoryInterface::createAuthorizeErrorResponse()
     */
    public function createAuthorizeErrorResponse(Error $error, AuthorizeRequest $request)
    {
        $response = new AuthorizeErrorResponse();
        $response->setError($error);
        $response->setRedirectUri($request->getRedirectUri());
        $response->setState($request->getState());
        
        return $response;
    }


    /**
     * {@inheritdoc}
     * @see \InoOicServer\Oic\Authorize\Response\ResponseFactoryInterface::createClientErrorResponse()
     */
    public function createClientErrorResponse(Error $error)
    {
        $response = new ClientErrorResponse();
        $response->setError($error);
        
        return $response;
    }
}