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
use InoOicServer\Util\OptionsTrait;


class HttpService implements HttpServiceInterface
{
    
    use OptionsTrait;

    /**
     * @var AuthorizeRequestFactoryInterface
     */
    protected $authorizeRequestFactory;

    protected $responseHandlers = array(
        'InoOicServer\Oic\Authorize\Response\ClientErrorResponse' => 'createHttpResponseFromClientError',
        'InoOicServer\Oic\Authorize\Response\AuthorizeErrorResponse' => 'createHttpResponseFromAuthorizeError',
        'InoOicServer\Oic\Authorize\Response\AuthorizeResponse' => 'createHttpResponseFromAuthorizeResponse'
    );

    protected $redirectHandlers = array(
        Redirect::TO_AUTHENTICATION => 'createHttpResponseFromRedirectToAuthentication',
        Redirect::TO_RESPONSE => 'createHttpResponseFromRedirectToResponse',
        Redirect::TO_URL => 'createHttpResponseFromRedirectToUrl'
    );


    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }


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


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Http\HttpServiceInterface::createAuthorizeRequest()
     */
    public function createAuthorizeRequest(Http\Request $httpRequest)
    {
        return $this->getAuthorizeRequestFactory()->createRequest($httpRequest);
    }


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Authorize\Http\HttpServiceInterface::createHttpResponse()
     */
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
    {
        $redirectType = $redirect->getType();
        if (! isset($this->redirectHandlers[$redirectType])) {
            throw new \RuntimeException(sprintf("Unknown redirect type '%s'", $redirectType));
        }
        
        $redirectHandler = $this->redirectHandlers[$redirectType];
        if (! method_exists($this, $redirectHandler)) {
            throw new \RuntimeException(sprintf("Non-existent redirect handler '%s' for redirect type '%s", $redirectHandler, $redirectType));
        }
        
        return call_user_func(array(
            $this,
            $redirectHandler
        ), $redirect);
    }


    public function createHttpResponseFromRedirectToAuthentication(Redirect $redirect)
    {}


    public function createHttpResponseFromRedirectToResponse(Redirect $redirect)
    {}


    public function createHttpResponseFromRedirectToUrl(Redirect $redirect)
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