<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;


class Token extends AbstractDispatcher
{

    /**
     * The token request object.
     * 
     * @var Request\Token
     */
    protected $_tokenRequest = NULL;

    /**
     * The token response object.
     * 
     * @var Response\Token
     */
    protected $_tokenResponse = NULL;


    /**
     * Sets the token request object.
     * 
     * @param Request\Token $request
     */
    public function setTokenRequest (Request\Token $request)
    {
        $this->_tokenRequest = $request;
    }


    /**
     * Returns the token request object.
     * 
     * @return Request\Token
     */
    public function getTokenRequest ()
    {
        return $this->_tokenRequest;
    }


    /**
     * Sets the token response object.
     * 
     * @param Response\Token $response
     */
    public function setTokenResponse (Response\Token $response)
    {
        $this->_tokenResponse = $response;
    }


    public function getTokenResponse ()
    {
        return $this->_tokenResponse;
    }


    public function dispatch ()
    {
        $request = $this->getTokenRequest();
        if (! $request) {
            throw new GeneralException\MissingDependencyException('token request');
        }
        
        /*
         * Validate request
         */
        if (! $request->isValid()) {
            throw new Exception\InvalidRequestException($request->getInvalidReasons());
        }
        
        /*
         * Validate client
         */
        $clientRegistry = $this->getClientRegistry();
        if (! $clientRegistry) {
            throw new GeneralException\MissingDependencyException('client registry');
        }
        
        $client = $clientRegistry->getClientById($request->getClientId());
        if (! $client) {
            throw new Exception\InvalidClientException($request->getClientId());
        }
        
        /*
         * Authenticate client
         */
        // [..]
        

        /*
         * Retrieve and validate the authorization code.
         */
        $sessionManager = $this->getSessionManager();
        if (! $sessionManager) {
            throw new GeneralException\MissingDependencyException('session manager');
        }
        
        $authorizationCode = $sessionManager->getAuthorizationCode($request->getCode());
        if (! $authorizationCode) {
            //throw new Exception\InvalidAuthorizationCodeException($request->getCode());
            // return error - authorization code not found
        }
        
        if ($authorizationCode->isExpired()) {
            // return expire error
        }
        
        /*
         * Retrieve session for the provided authorization code.
         */
        $session = $sessionManager->getSessionForAuthorizationCode($authorizationCode);
        if (! $session) {
            //throw new Exception\InvalidAuthorizationCodeException($request->getCode());
            // return error - session for authorization code not found
        }
        
        /*
         * Create the access token object.
         */
        $accessToken = $sessionManager->createAccessToken($session, $client);
        
        // return response
    }
}