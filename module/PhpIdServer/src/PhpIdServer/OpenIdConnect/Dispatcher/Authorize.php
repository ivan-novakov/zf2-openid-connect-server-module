<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\Client\Client;
use PhpIdServer\Authentication;
use PhpIdServer\User\User;
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
    protected $_context = NULL;

    /**
     * The response object.
     * 
     * @var Response\Authorize\Simple
     */
    protected $_response = NULL;

    /**
     * The redirect URI for the current client.
     * 
     * @var string
     */
    protected $_clientRedirectUri = NULL;


    /**
     * Sets the context object.
     * 
     * @param AuthorizeContext $context
     */
    public function setContext (AuthorizeContext $context)
    {
        $this->_context = $context;
    }


    /**
     * Returns the context object.
     * 
     * @return AuthorizeContext
     */
    public function getContext ($throwException = false)
    {
        if ($throwException && ! ($this->_context instanceof AuthorizeContext)) {
            throw new GeneralException\MissingDependencyException('authorize context');
        }
        return $this->_context;
    }


    /**
     * Sets the authorize response object.
     * 
     * @param Response\Authorize\Simple $response
     */
    public function setAuthorizeResponse (Response\Authorize\Simple $response)
    {
        $this->_response = $response;
    }


    /**
     * Returns the authorize response object.
     * 
     * @return Response\Authorize\Simple
     */
    public function getAuthorizeResponse ($throwException = false)
    {
        if ($throwException && ! ($this->_response instanceof Response\Authorize\Simple)) {
            throw new GeneralException\MissingDependencyException('authorize response');
        }
        return $this->_response;
    }


    /**
     * @throws GeneralException\MissingDependencyException
     * @return mixed
     */
    public function preDispatch ()
    {
        $context = $this->getContext(true);
        
        $request = $context->getRequest();
        if (! $request) {
            return $this->_clientErrorResponse(Error::ERROR_CLIENT_INVALID_REQUEST, 'no request data');
        }
        
        $clientId = $request->getClientId();
        if (! $clientId) {
            return $this->_clientErrorResponse(Error::ERROR_CLIENT_INVALID_REQUEST, 'no client ID');
        }
        
        $registry = $this->getClientRegistry(true);
        $client = $registry->getClientById($clientId);
        if (! $client) {
            return $this->_clientErrorResponse(Error::ERROR_CLIENT_INVALID_CLIENT, 'client not found');
        }
        
        /*
         * Validate client
         */
        // [...]
        

        $context->setClient($client);
    }


    /**
     * Dispatches the response.
     * 
     * @return Response\ResponseInterface
     */
    public function dispatch ()
    {
        $context = $this->getContext(true);
        
        $client = $context->getClient();
        if (! ($client instanceof Client)) {
            return $this->_clientErrorResponse(Error::ERROR_INVALID_REQUEST, 'no client data in context');
        }
        
        $this->_clientRedirectUri = $client->getRedirectUri();
        if (! $this->_clientRedirectUri) {
            return $this->_clientErrorResponse(Error::ERROR_INVALID_REQUEST, 'no redirect uri found');
        }
        
        $user = $context->getUser();
        if (! ($user instanceof User)) {
            return $this->_errorResponse(Error::ERROR_INVALID_REQUEST, 'no user in context');
        }
        
        $authenticationInfo = $context->getAuthenticationInfo();
        if (! ($authenticationInfo instanceof Authentication\Info)) {
            return $this->_errorResponse(Error::ERROR_INVALID_REQUEST, 'no authentication info in context');
        }
        
        $request = $context->getRequest();
        if (! ($request instanceof Request\Authorize\Simple)) {
            return $this->_errorResponse(Error::ERROR_INVALID_REQUEST, 'no request in context');
        }
        
        $response = $this->getAuthorizeResponse(true);
        $sessionManager = $this->getSessionManager(true);
        
        $session = $sessionManager->createSession($user, $authenticationInfo);
        $authorizationCode = $sessionManager->createAuthorizationCode($session, $client);
        
        $response->setAuthorizationCode($authorizationCode->getCode());
        $response->setRedirectLocation($client->getRedirectUri());
        
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
    protected function _errorResponse ($message, $description = NULL)
    {
        if (! $this->_clientRedirectUri) {
            return $this->_clientErrorResponse($message, $description);
        }
        
        $errorResponse = $this->_createErrorResponse();
        $errorResponse->setRedirectLocation($this->_clientRedirectUri);
        $errorResponse->setError($message, $description);
        
        return $errorResponse;
    }


    /**
     * Returns a client error response object.
     * 
     * @param string $message
     * @param string $description
     * @return Authorize\Error
     */
    protected function _clientErrorResponse ($message, $description = NULL)
    {
        $errorResponse = $this->_createErrorResponse();
        $errorResponse->setInvalidClientError($message, $description);
        
        return $errorResponse;
    }


    /**
     * Creates and returns a generic response object.
     * 
     * @return Authorize\Error
     */
    protected function _createErrorResponse ()
    {
        $response = $this->getAuthorizeResponse(true);
        
        return new Response\Authorize\Error($response->getRawHttpResponse());
    }
}