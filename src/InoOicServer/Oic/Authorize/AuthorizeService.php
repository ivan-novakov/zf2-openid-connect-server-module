<?php

namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\Authorize\Response\ResponseInterface;
use InoOicServer\Oic\AuthSession\AuthSessionServiceInterface;
use InoOicServer\Oic\Client\ClientServiceInterface;
use InoOicServer\Oic\Authorize\Context\ContextServiceInterface;
use InoOicServer\Oic\Session\SessionServiceInterface;
use InoOicServer\Oic\AuthCode\AuthCodeServiceInterface;


class AuthorizeService
{

    /**
     * @var ContextServiceInterface
     */
    protected $contextService;

    /**
     * @var ClientServiceInterface
     */
    protected $clientService;

    /**
     * @var AuthSessionServiceInterface
     */
    protected $authSessionService;

    /**
     * @var SessionServiceInterface
     */
    protected $sessionService;

    /**
     * @var AuthCodeServiceInterface
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
    public function __construct(ClientServiceInterface $clientService, ContextServiceInterface $contextService, 
        AuthSessionServiceInterface $authSessionService, SessionServiceInterface $sessionService, 
        AuthCodeServiceInterface $authCodeService)
    {
        $this->setClientService($clientService);
        $this->setContextService($contextService);
        $this->setAuthSessionService($authSessionService);
        $this->setSessionService($sessionService);
        $this->setAuthCodeService($authCodeService);
    }


    /**
     * @return ContextServiceInterface
     */
    public function getContextService()
    {
        return $this->contextService;
    }


    /**
     * @param ContextServiceInterface $contextService
     */
    public function setContextService(ContextServiceInterface $contextService)
    {
        $this->contextService = $contextService;
    }


    /**
     * @return ClientServiceInterface
     */
    public function getClientService()
    {
        return $this->clientService;
    }


    /**
     * @param ClientServiceInterface $clientService
     */
    public function setClientService(ClientServiceInterface $clientService)
    {
        $this->clientService = $clientService;
    }


    /**
     * @return AuthSesAuthSessionServiceInterface
     */
    public function getAuthSessionService()
    {
        return $this->authSessionService;
    }


    /**
     * @param AuthSessionServiceInterface $authSession
     */
    public function setAuthSessionService(AuthSessionServiceInterface $authSessionService)
    {
        $this->authSessionService = $authSessionService;
    }


    /**
     * @return SessionServiceInterface
     */
    public function getSessionService()
    {
        return $this->sessionService;
    }


    /**
     * @param SessionServiceInterface $sessionService
     */
    public function setSessionService(SessionServiceInterface $sessionService)
    {
        $this->sessionService = $sessionService;
    }


    /**
     * @return AuthCodeServiceInterface
     */
    public function getAuthCodeService()
    {
        return $this->authCodeService;
    }


    /**
     * @param AuthCodeServiceInterface $authCodeService
     */
    public function setAuthCodeService(AuthCodeServiceInterface $authCodeService)
    {
        $this->authCodeService = $authCodeService;
    }


    public function processRequest(AuthorizeRequest $request)
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
        
        // if true, check if there is valid session bound to the authn session to be reused or create new one and
        // redirect to response action
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