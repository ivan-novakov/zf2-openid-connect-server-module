<?php

namespace PhpIdServer\OpenIdConnect\Dispatcher;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\OpenIdConnect\Request;
use PhpIdServer\OpenIdConnect\Response;
use PhpIdServer\OpenIdConnect\Entity;


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


    /**
     * Returns the token response object.
     * 
     * @return Response\Token
     */
    public function getTokenResponse ()
    {
        return $this->_tokenResponse;
    }


    /**
     * Dispatchers the request and returns the response.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
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
            _dump($request->getInvalidReasons());
            return $this->_errorResponse(Response\Token::ERROR_INVALID_REQUEST, sprintf("Reasons: %s", implode(', ', $request->getInvalidReasons())));
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
            return $this->_errorResponse(Response\Token::ERROR_INVALID_CLIENT, sprintf("Client with ID '%s' not found in registry", $request->getClientId()));
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
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT, 'No such authorization code');
        }
        
        if ($authorizationCode->isExpired()) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT, 'Expired authorization code');
        }
        
        /*
         * Retrieve session for the provided authorization code.
         */
        $session = $sessionManager->getSessionForAuthorizationCode($authorizationCode);
        if (! $session) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT, 'No session associated with the authorization code');
        }
        
        /*
         * Create the access token object.
         */
        $accessToken = $sessionManager->createAccessToken($session, $client);
        
        $accessTokenEntity = new Entity\Token(array(
            Entity\Token::FIELD_ACCESS_TOKEN => $accessToken->getToken(), 
            Entity\Token::FIELD_TOKEN_TYPE => $accessToken->getType(), 
            Entity\Token::FIELD_EXPIRES_IN => $accessToken->expiresIn(), 
            Entity\Token::FIELD_REFRESH_TOKEN => 'not set', 
            Entity\Token::FIELD_ID_TOKEN => 'not set'
        ));
        
        return $this->_validResponse($accessTokenEntity);
    }


    /**
     * Returns a valid response based on the provided token data.
     * 
     * @param Entity\Token $entityToken
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
    protected function _validResponse (Entity\Token $entityToken)
    {
        $response = $this->getTokenResponse();
        if (! $response) {
            throw new GeneralException\MissingDependencyException('token response');
        }
        
        $response->setTokenEntity($entityToken);
        
        return $response;
    }


    /**
     * Returns an error response with the provided message.
     * 
     * @param string $message
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
    protected function _errorResponse ($message)
    {
        $response = $this->getTokenResponse();
        if (! $response) {
            throw new GeneralException\MissingDependencyException('token response');
        }
        
        $response->setError($message);
        
        return $response;
    }
}