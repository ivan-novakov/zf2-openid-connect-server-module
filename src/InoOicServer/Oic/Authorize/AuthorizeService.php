<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\AuthCode\AuthCodeService;
use InoOicServer\Oic\Session\SessionService;
use InoOicServer\Oic\Client\ClientService;
use InoOicServer\Oic\Authorize\Context\ContextService;
use InoOicServer\Oic\Authorize\Request\Request;
use InoOicServer\Oic\Authorize\Response\ResponseInterface;


class AuthorizeService
{

    /**
     * @var ContextService
     */
    protected $contextService;

    /**
     * @var ClientService
     */
    protected $clientService;

    /**
     * @var SessionService
     */
    protected $sessionService;

    /**
     * @var AuthCodeService
     */
    protected $authCodeService;


    /**
     * Constructor.
     * 
     * @param ClientService $clientService
     * @param ContextService $contextService
     * @param SessionService $sessionService
     * @param AuthCodeService $authCodeService
     */
    public function __construct(ClientService $clientService, ContextService $contextService, 
        SessionService $sessionService, AuthCodeService $authCodeService)
    {
        $this->setClientService($clientService);
        $this->setContextService($contextService);
        $this->setSessionService($sessionService);
        $this->setAuthCodeService($authCodeService);
    }


    /**
     * @return ContextService
     */
    public function getContextService()
    {
        return $this->contextService;
    }


    /**
     * @param ContextService $contextService
     */
    public function setContextService(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }


    /**
     * @return ClientService
     */
    public function getClientService()
    {
        return $this->clientService;
    }


    /**
     * @param ClientService $clientService
     */
    public function setClientService(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }


    /**
     * @return SessionService
     */
    public function getSessionService()
    {
        return $this->sessionService;
    }


    /**
     * @param SessionService $sessionService
     */
    public function setSessionService(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }


    /**
     * @return AuthCodeService
     */
    public function getAuthCodeService()
    {
        return $this->authCodeService;
    }


    /**
     * @param AuthCodeService $authCodeService
     */
    public function setAuthCodeService(AuthCodeService $authCodeService)
    {
        $this->authCodeService = $authCodeService;
    }


    public function processRequest(Request $request)
    {
        // identify and validate client (application)
        $client = $this->getClientService()->fetchClient($request->getClientId(), $request->getRedirectUri());
        
        // create new Authorize\Context
        $contextService = $this->getContextService();
        $context = $contextService->createContext();
        
        // save Authorize\Request to context
        $context->setAuthorizeRequest($request);
        
        // save client to context
        // ?? is it necessary?
        
        // check if there is active/valid authentication session
        // if true, check if there is valid session bound to the authn session to be reused or create new one and redirect to response action
        // otherwise redirect to authentication
    }


    public function processResponse(ResponseInterface $response = null)
    {
        // check context
        // check client and request from context
        // check authorize request (from context), if there is active/valid (authentication) session,
        // if true, check for existing auth. code and create it if missing, then skip to create response
        // otherwise check user authentication and:
        // if a valid session exists for the user, reuse it
        // or create new session
        // check for auth. code and:
        // if a valid code exists for the user and the client, reuse it
        // or create a new one
        // create and return the corresponding Authorize\Response
    }
}