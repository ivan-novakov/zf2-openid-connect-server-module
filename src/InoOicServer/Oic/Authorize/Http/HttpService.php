<?php

namespace InoOicServer\Oic\Authorize\Http;

use Zend\Http;
use InoOicServer\Oic\Authorize\Response\AuthorizeResponse;
use InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse;
use InoOicServer\Oic\Authorize\Response\ClientErrorResponse;
use InoOicServer\Oic\Authorize\Response\ResponseInterface;
use InoOicServer\Oic\Authorize\Redirect;
use InoOicServer\Oic\Authorize\Result;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactoryInterface;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactory;


class HttpService implements HttpServiceInterface
{

    /**
     * @var AuthorizeRequestFactoryInterface
     */
    protected $authorizeRequestFactory;

    protected $responseHandlers = array(
        'InoOicServer\Oic\Authorize\Response\ClientErrorResponse' => 'createHttpResponseFromClientError',
        'InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse' => 'createHttpResponseFromAuthorizeError',
        'InoOicServer\Oic\Authorize\Response\AuthorizeResponse' => 'createHttpResponseFromAuthorizeResponse'
    );


    /**
     * @return AuthorizeRequestFactoryInterface
     */
    public function getAuthorizeRequestFactory()
    {
        if (! $this->authorizeRequestFactory instanceof AuthorizeRequestFactoryInterface) {
            $this->authorizeRequestFactory = new AuthorizeRequestFactory();
        }
        
        return $this->authorizeRequestFactory;
    }


    /**
     * @param AuthorizeRequestFactoryInterface $authorizeRequestFactory
     */
    public function setAuthorizeRequestFactory(AuthorizeRequestFactoryInterface $authorizeRequestFactory)
    {
        $this->authorizeRequestFactory = $authorizeRequestFactory;
    }


    public function createAuthorizeRequest(Http\Request $httpRequest)
    {
        return $this->getAuthorizeRequestFactory()->createRequest($httpRequest);
    }


    public function createHttpResponse(Result $result)
    {
        if ($result->getType() === Result::TYPE_REDIRECT) {
            return $this->createHttpResponseFromRedirect($result->getRedirect());
        }
        
        $httpResponse = $this->createHttpResponseFromResponse($result->getResponse());
        /*
        $httpResponse = new Http\Response();
        $httpResponse->setStatusCode(400);
        */
        return $httpResponse;
    }


    public function createHttpResponseFromRedirect(Redirect $redirect)
    {}


    public function createHttpResponseFromResponse(ResponseInterface $response)
    {
        $responseClass = get_class($response);
        if (! isset($this->responseHandlers[$responseClass])) {
            throw new \RuntimeException(sprintf("Unknown response class '%s'", $responseClass));
        }
        
        $responseHandler = $this->responseHandlers[$responseClass];
        if (! method_exists($this, $responseHandler)) {
            throw new \RuntimeException(sprintf("Non-existent response handler '%s' for response class '%s'", $responseHandler, $responseClass));
        }
        
        return call_user_func(array(
            $this,
            $responseHandler
        ), $response);
    }


    public function createHttpResponseFromClientError(ClientErrorResponse $clientErrorResponse)
    {
        $error = $clientErrorResponse->getError();
        
        $httpResponse = new Http\Response();
        $httpResponse->setStatusCode(400);
        $httpResponse->setContent(sprintf("Client error '%s' (%s)", $error->getMessage(), $error->getDescription()));
        
        return $httpResponse;
    }


    public function createHttpResponseFromAuthorizeError(AuthorizeErrorResponse $authorizeErrorResponse)
    {}


    public function createHttpResponseFromAuthorizeResponse(AuthorizeResponse $authorizeResponse)
    {}
}