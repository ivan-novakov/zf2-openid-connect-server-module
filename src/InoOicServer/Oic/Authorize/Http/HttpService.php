<?php

namespace InoOicServer\Oic\Authorize\Http;

use InoOicServer\Oic\Authorize\Result;
use Zend\Http;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactoryInterface;
use InoOicServer\Oic\Authorize\AuthorizeRequestFactory;


class HttpService implements HttpServiceInterface
{

    /**
     * @var AuthorizeRequestFactoryInterface
     */
    protected $authorizeRequestFactory;


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
        $httpResponse = new Http\Response();
        $httpResponse->setStatusCode(501);
        
        return $httpResponse;
    }
}