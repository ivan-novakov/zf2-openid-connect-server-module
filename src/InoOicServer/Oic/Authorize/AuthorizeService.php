<?php
namespace InoOicServer\Oic\Authorize;

use InoOicServer\Oic\Error;
use InoOicServer\Oic\AuthSession\AuthSession;
use InoOicServer\Oic\AuthSession\AuthSessionServiceInterface;
use InoOicServer\Oic\Client\Client;
use InoOicServer\Oic\Client\ClientServiceInterface;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Session\SessionServiceInterface;
use InoOicServer\Oic\AuthCode\AuthCodeServiceInterface;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Authorize\Response\ResponseInterface;
use InoOicServer\Oic\Authorize\Response\ResponseFactoryInterface;
use InoOicServer\Oic\Authorize\Response\ResponseFactory;
use InoOicServer\Oic\Authorize\Context\ContextServiceInterface;
use InoOicServer\Oic\User\UserInterface;
use InoOicServer\Oic\Client\Exception\ClientExceptionInterface;

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
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * Constructor.
     *
     * @param ClientService $clientService
     * @param ContextService $contextService
     * @param SessionService $sessionService
     * @param AuthCodeService $authCodeService
     */
    public function __construct(ClientServiceInterface $clientService, ContextServiceInterface $contextService, AuthSessionServiceInterface $authSessionService, SessionServiceInterface $sessionService, AuthCodeServiceInterface $authCodeService)
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
     * @return AuthSessionServiceInterface
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

    /**
     * @return ResponseFactoryInterface
     */
    public function getResponseFactory()
    {
        if (! $this->responseFactory instanceof ResponseFactoryInterface) {
            $this->responseFactory = new ResponseFactory();
        }
        
        return $this->responseFactory;
    }

    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function setResponseFactory(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function processRequest(AuthorizeRequest $request)
    {
        // FIXME - perform request validation
        
        // identify and validate client (application)
        try {
            $client = $this->getClientService()->fetchClientFromAuthorizeRequest($request);
        } catch (ClientExceptionInterface $e) {
            return $this->createClientErrorResult('invalid_request', sprintf("[%s] %s", get_class($e), $e->getMessage()));
        }
        
        // create new Authorize\Context
        $contextService = $this->getContextService();
        $context = $contextService->createContext();
        
        // save Authorize\Request to context
        $context->setAuthorizeRequest($request);
        
        // save client to context
        // ?? is it necessary?
        
        // check if there is active/valid session
        $session = $this->fetchSessionFromRequest($request);
        if ($session) {
            // check for auth code and create new if non-existent
            $authCode = $this->getAuthCodeService()->initAuthCodeFromSession($session, $client, $request->getScope());
            
            // create and return response with the code
            return $this->createResponseResult($authCode, $request, $session);
        }
        
        // check if there is active/valid auth session
        $authSession = $this->fetchAuthSessionFromRequest($request);
        if ($authSession) {
            $authCode = $this->initAuthCodeFromAuthSession($authSession, $client, $request);
            
            // create and return response with the code
            return $this->createResponseResult($authCode, $request);
        }
        
        // otherwise redirect to authentication
        return $this->createRedirectToAuthenticationResult();
    }

    public function processResponse(ResponseInterface $response = null)
    {
        // check context
        $contextService = $this->getContextService();
        $context = $contextService->loadContext();
        if (! $context) {
            // client error
            return $this->createClientErrorResult('invalid_request', 'Missing context');
        }
        
        $contextService->clearContext();
        
        // get request from context
        $request = $context->getAuthorizeRequest();
        if (! $request) {
            // client error
            return $this->createClientErrorResult('invalid_request', 'Wrong context');
        }
        
        // resolve client from request
        $client = $this->getClientService()->fetchClient($request->getClientId());
        if (! $client) {
            // client error
            return $this->createClientErrorResult('invalid_request', 'Client not found');
        }
        
        // check authentication status
        $authStatus = $context->getAuthStatus();
        if (! $authStatus) {
            // authorize error
            return $this->__createAuthorizeErrorResult('invalid_request', 'Missing authentication info', $request);
        }
        
        if (! $authStatus->isAuthenticated()) {
            // authorize error
            return $this->__createAuthorizeErrorResult('invalid_request', 'User not authenticated', $request);
        }
        
        $user = $authStatus->getIdentity();
        if (! $user instanceof UserInterface) {
            // authorize error
            return $this->__createAuthorizeErrorResult('invalid_request', 'Invalid user identity', $request);
        }
        
        // check for existing auth session
        // fetch auth session by user/method
        
        // create new auth session
        $authSession = $this->getAuthSessionService()->createSession($authStatus);
        $this->getAuthSessionService()->saveSession($authSession);
        
        // if a valid session exists for the user, reuse it
        $session = $this->getSessionService()->fetchSessionByUser($user);
        if (! $session) {
            $session = $this->getSessionService()->createSession($authSession);
            $this->getSessionService()->saveSession($session);
        }
        
        // check for auth. code and:
        $authCode = $this->getAuthCodeService()->fetchAuthCodeBySession($session, $client);
        // if a valid code exists for the user and the client, reuse it
        // or create a new one
        if (! $authCode) {
            $authCode = $this->getAuthCodeService()->createAuthCode($session, $client);
            $this->getAuthCodeService()->saveAuthCode($authCode);
        }
        
        // create and return the corresponding Authorize\Response
        return $this->createResponseResult($authCode, $request, $session);
    }

    public function initAuthCodeFromAuthSession(AuthSession $authSession, Client $client, AuthorizeRequest $request)
    {
        $session = $this->getSessionService()->initSessionFromAuthSession($authSession, $request->getNonce());
        $authCode = $this->getAuthCodeService()->initAuthCodeFromSession($session, $client, $request->getScope());
        
        return $authCode;
    }

    public function fetchAuthSessionFromRequest(AuthorizeRequest $request)
    {
        if ($authSessionId = $request->getAuthenticationSessionId()) {
            return $this->getAuthSessionService()->fetchSession($authSessionId);
        }
        
        return null;
    }

    public function fetchSessionFromRequest(AuthorizeRequest $request)
    {
        if ($sessionId = $request->getSessionId()) {
            return $this->getSessionService()->fetchSession($sessionId);
        }
        
        return null;
    }

    public function createResponseResult(AuthCode $authCode, AuthorizeRequest $request, Session $session = null)
    {
        // TEST
        if (null === $session) {
            $session = $this->resolveSessionByAuthCode($authCode);
        }
        
        $response = $this->getResponseFactory()->createAuthorizeResponse($authCode, $request, $session);
        $result = Result::constructResponseResult($response);
        
        return $result;
    }

    protected function __createAuthorizeErrorResult($message, $description, $request)
    {
        $error = new Error();
        $error->setMessage($message);
        $error->setDescription($description);
        
        return $this->createAuthorizeErrorResult($error, $request);
    }

    public function createAuthorizeErrorResult(Error $error, AuthorizeRequest $request)
    {
        $response = $this->getResponseFactory()->createAuthorizeErrorResponse($error, $request);
        $result = Result::constructResponseResult($response);
        
        return $result;
    }

    public function createRedirectToAuthenticationResult()
    {
        $redirect = new Redirect(Redirect::TO_AUTHENTICATION);
        $result = Result::constructRedirectResult($redirect);
        
        return $result;
    }

    public function createClientErrorResult($message, $description = null)
    {
        $error = new Error();
        $error->setMessage($message);
        $error->setDescription($description);
        
        return $this->createClientErrorResultFromError($error);
    }

    public function createClientErrorResultFromError(Error $error)
    {
        $response = $this->getResponseFactory()->createClientErrorResponse($error);
        $result = Result::constructResponseResult($response);
        
        return $result;
    }

    protected function resolveSessionByAuthCode(AuthCode $authCode)
    {
        $session = $authCode->getSession();
        if ($session instanceof Session) {
            return $session;
        }
        
        $session = $this->getSessionService()->fetchSessionByCode($authCode);
        if ($session instanceof Session) {
            return $session;
        }
        
        throw new Exception\AuthCodeWithoutSessionException(sprintf("Authorization code '%s' has no session", $authCode->getCode()));
    }
}