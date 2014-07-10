<?php

namespace InoOicServer\Mvc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use InoOicServer\Oic\Authorize\Http\HttpServiceInterface;
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
        $httpService = $this->getHttpService();
        $httpRequest = $this->getRequest();
        
        $authorizeRequest = $httpService->createAuthorizeRequest($this->getRequest());
        $result = $this->getAuthorizeService()->processRequest($authorizeRequest);
        
        $httpResponse = $httpService->createHttpResponse($result);
        return $httpResponse;
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