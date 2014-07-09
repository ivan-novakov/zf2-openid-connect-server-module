<?php

namespace InoOicServer\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use InoOicServer\Oic\Authorize\AuthorizeService;


class AuthorizeController extends AbstractActionController
{

    /**
     * @var HttpServiceInterface
     */
    protected $httpService;

    /**
     * @var AuthorizeService
     */
    protected $authorizeService;


    /**
     * Constructor.
     * 
     * @param HttpServiceInterface $httpService
     */
    public function __construct(HttpServiceInterface $httpService, AuthorizeService $authorizeService)
    {
        $this->setHttpService($httpService);
        $this->setAuthorizeService($authorizeService);
    }


    /**
     * @return HttpServiceInterface
     */
    public function getHttpService()
    {
        return $this->httpService;
    }


    /**
     * @param HttpServiceInterface $httpService
     */
    public function setHttpService(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }


    /**
     * @return AuthorizeService
     */
    public function getAuthorizeService()
    {
        return $this->authorizeService;
    }


    /**
     * @param AuthorizeService $authorizeService
     */
    public function setAuthorizeService(AuthorizeService $authorizeService)
    {
        $this->authorizeService = $authorizeService;
    }


    public function authorizeAction()
    {
        // create authorize request from http request (request factory)
        /*
        $request = $this->getAuthorizeRequestFactory($httpRequest);
        */
        
        // process authorize request (via authorize service)
        /*
        $response = $this->getAuthorizeService()->processRequest($request);
        */
        
        // handle result, which can be one of these (http response factory)
        // - redirect to response endpoint (if there is valid context)
        // - redirect to authentication
        // - error, client known - error redirect to client
        // - error, client not known - show HTML message directly
        /*
        $httpResponse = $this->getHttpResponseFactory($reponse);
        return $httpResponse;
        */
        return $this->getResponse();
    }


    public function responseAction()
    {
        // process authorize response (via authorize service)
        /*
        $response = $this->getAuthorizeService()->processResponse();
        */
        // handle result, which can be one of these:
        // - redirect to client (valid response)
        // - custom redirect (to registration form)
        // - error, client known - error redirect to client
        // - error, client not known - show HTML message directly
        /*
        $httpResponse = $this->getHttpResponseFactory($reponse);
        return $httpResponse;
        */
    }
}