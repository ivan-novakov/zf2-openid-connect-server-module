<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\User\DataConnector\DataConnectorInterface;
use PhpIdServer\Client\Client;
use PhpIdServer\Authentication;
use PhpIdServer\User\UserInterface;
use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\Context\AuthorizeContext;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response\Authorize\Error;


class Authorize extends AbstractDispatcher
{

    /**
     * The authorize context object.
     * 
     * @var AuthorizeContext
     */
    protected $context = NULL;

    /**
     * The response object.
     * 
     * @var Response\Authorize\Simple
     */
    protected $response = NULL;

    /**
     * The amount of seconds after an invalid authentication, when it is possible to perform another authentication.
     * 
     * @var integer
     */
    protected $previousAuthenticationErrorTimeout = 10;

    /**
     * Data connector.
     * 
     * @var DataConnectorInterface
     */
    protected $dataConnector = null;


    /**
     * Sets the context object.
     * 
     * @param AuthorizeContext $context
     */
    public function setContext(AuthorizeContext $context)
    {
        $this->context = $context;
    }


    /**
     * Returns the context object.
     * 
     * @return AuthorizeContext
     */
    public function getContext($throwException = false)
    {
        if ($throwException && ! ($this->context instanceof AuthorizeContext)) {
            throw new GeneralException\MissingDependencyException('authorize context');
        }
        return $this->context;
    }


    /**
     * Sets the authorize response object.
     * 
     * @param Response\Authorize\Simple $response
     */
    public function setAuthorizeResponse(Response\Authorize\Simple $response)
    {
        $this->response = $response;
    }


    /**
     * Returns the authorize response object.
     * 
     * @return Response\Authorize\Simple
     */
    public function getAuthorizeResponse($throwException = false)
    {
        if ($throwException && ! ($this->response instanceof Response\Authorize\Simple)) {
            throw new GeneralException\MissingDependencyException('authorize response');
        }
        return $this->response;
    }


    /**
     * Sets the data connector.
     * 
     * @param DataConnectorInterface $dataConnector
     */
    public function setDataConnector(DataConnectorInterface $dataConnector)
    {
        $this->dataConnector = $dataConnector;
    }


    /**
     * Returns the data connector.
     * 
     * @param boolean $throwException
     * @throws GeneralException\MissingDependencyException
     * @return DataConnectorInterface
     */
    public function getDataConnector($throwException = false)
    {
        if (null === $this->dataConnector && $throwException) {
            throw new GeneralException\MissingDependencyException('data connector');
        }
        
        return $this->dataConnector;
    }


    /**
     * @throws GeneralException\MissingDependencyException
     * @return mixed
     */
    public function preDispatch()
    {
        $context = $this->getContext(true);
        
        $request = $context->getRequest();
        if (! $request) {
            return $this->clientErrorResponse(Error::ERROR_CLIENT_INVALID_REQUEST, 'no request data');
        }
        
        $clientId = $request->getClientId();
        if (! $clientId) {
            return $this->clientErrorResponse(Error::ERROR_CLIENT_INVALID_REQUEST, 'no client ID');
        }
        
        $redirectUri = $request->getRedirectUri();
        if (! $redirectUri) {
            return $this->clientErrorResponse(Error::ERROR_CLIENT_INVALID_REQUEST, 'no client redirect URI');
        }
        
        $registry = $this->getClientRegistry(true);
        $client = $registry->getClientById($clientId);
        if (! $client) {
            return $this->clientErrorResponse(Error::ERROR_CLIENT_INVALID_CLIENT, 'client not found');
        }
        
        /*
         * Validate client
         */
        // [...]
        
        if (! $client->hasRedirectUri($redirectUri)) {
            return $this->clientErrorResponse(Error::ERROR_INVALID_REQUEST, 'redirect URI mismatch');
        }
        
        /*
         * Check if there has been an unsuccessful authentication attempt
         */
        $authenticationInfo = $context->getAuthenticationInfo();
        if ($authenticationInfo && ! $authenticationInfo->isExpired($this->previousAuthenticationErrorTimeout) && ($error = $authenticationInfo->getError())) {
            $description = $authenticationInfo->getErrorDescription();
            
            return $this->errorResponse(Error::ERROR_SERVER_ERROR, sprintf("Authentication error: '%s' (%s)", $error, $description));
        }
        
        $context->setClient($client);
    }


    /**
     * Dispatches the response.
     * 
     * @return Response\ResponseInterface
     */
    public function dispatch()
    {
        $context = $this->getContext(true);
        
        $client = $context->getClient();
        if (! ($client instanceof Client)) {
            return $this->clientErrorResponse(Error::ERROR_INVALID_REQUEST, 'no client data in context');
        }
        
        $user = $context->getUser();
        if (! ($user instanceof UserInterface)) {
            return $this->errorResponse(Error::ERROR_INVALID_REQUEST, 'no user in context');
        }
        
        $authenticationInfo = $context->getAuthenticationInfo();
        if (! ($authenticationInfo instanceof Authentication\Info)) {
            return $this->errorResponse(Error::ERROR_INVALID_REQUEST, 'no authentication info in context');
        }
        
        $request = $context->getRequest();
        if (! ($request instanceof Request\Authorize\Simple)) {
            return $this->errorResponse(Error::ERROR_INVALID_REQUEST, 'no request in context');
        }
        
        $dataConnector = $this->getDataConnector();
        if ($dataConnector instanceof DataConnectorInterface) {
            $dataConnector->populateUser($user);
        }
        
        $response = $this->getAuthorizeResponse(true);
        $sessionManager = $this->getSessionManager(true);
        
        $session = $sessionManager->createSession($user, $authenticationInfo);
        $authorizationCode = $sessionManager->createAuthorizationCode($session, $client);
        
        $response->setAuthorizationCode($authorizationCode->getCode());
        $response->setRedirectLocation($this->getRedirectUri());
        
        if ($state = $request->getState()) {
            $response->setState($state);
        }
        
        return $response;
    }


    /**
     * Returns a general error response object.
     * 
     * @param string $message
     * @param string $description
     * @return Authorize\Error
     */
    public function errorResponse($message, $description = NULL)
    {
        $redirectUri = $this->getContext(true)
            ->getRequest()
            ->getRedirectUri();
        
        if (! $redirectUri) {
            return $this->clientErrorResponse($message, $description);
        }
        
        $errorResponse = $this->createErrorResponse();
        $errorResponse->setRedirectLocation($redirectUri);
        $errorResponse->setError($message, $description);
        
        return $errorResponse;
    }


    /**
     * Returns an error response object of type 'server error'.
     * 
     * @param string $description
     * @return Authorize\Error
     */
    public function serverErrorResponse($description = NULL)
    {
        return $this->errorResponse(Error::ERROR_SERVER_ERROR, $description);
    }


    /**
     * Returns a client error response object.
     * 
     * @param string $message
     * @param string $description
     * @return Authorize\Error
     */
    protected function clientErrorResponse($message, $description = NULL)
    {
        $errorResponse = $this->createErrorResponse();
        $errorResponse->setInvalidClientError($message, $description);
        
        return $errorResponse;
    }


    /**
     * Creates and returns a generic response object.
     * 
     * @return Authorize\Error
     */
    protected function createErrorResponse()
    {
        $response = $this->getAuthorizeResponse(true);
        
        return new Response\Authorize\Error($response->getRawHttpResponse());
    }


    protected function getRedirectUri()
    {
        return $this->getContext(true)
            ->getRequest()
            ->getRedirectUri();
    }
}