<?php

namespace InoOicServer\OpenIdConnect\Dispatcher;

use InoOicServer\Entity\EntityFactory;
use InoOicServer\Session\Token\AccessToken;
use InoOicServer\Entity\EntityFactoryInterface;
use InoOicServer\General\Exception as GeneralException;
use InoOicServer\OpenIdConnect\Request;
use InoOicServer\OpenIdConnect\Response;
use InoOicServer\OpenIdConnect\Entity;
use InoOicServer\Client;
use InoOicServer\User\UserInterface;
use InoOicServer\Session\Session;
use InoOicServer\OpenIdConnect\IdToken\IdTokenFactory;
use InoOicServer\Server\ServerInfo;
use InoOicServer\OpenIdConnect\IdToken\IdToken;


/**
 * Dispatches a token request.
 *
 */
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
     * The token factory object.
     * 
     * @var EntityFactoryInterface
     */
    protected $_tokenFactory = NULL;

    /**
     * Client authentication manager.
     * 
     * @var Client\Authentication\Manager 
     */
    protected $_clientAuthenticationManager = NULL;

    /**
     * @var IdTokenFactory
     */
    protected $_idTokenFactory;

    /**
     * @var ServerInfo
     */
    protected $_serverInfo;


    /**
     * Sets the token request object.
     * 
     * @param Request\Token $request
     */
    public function setTokenRequest(Request\Token $request)
    {
        $this->_tokenRequest = $request;
    }


    /**
     * Returns the token request object.
     * 
     * @return Request\Token
     */
    public function getTokenRequest($throwException = false)
    {
        if ($throwException && ! ($this->_tokenRequest instanceof Request\Token)) {
            throw new GeneralException\MissingDependencyException('request token');
        }
        return $this->_tokenRequest;
    }


    /**
     * Sets the token response object.
     * 
     * @param Response\Token $response
     */
    public function setTokenResponse(Response\Token $response)
    {
        $this->_tokenResponse = $response;
    }


    /**
     * Returns the token response object.
     * 
     * @return Response\Token
     */
    public function getTokenResponse($throwException = false)
    {
        if ($throwException && ! ($this->_tokenResponse instanceof Response\Token)) {
            throw new GeneralException\MissingDependencyException('response token');
        }
        return $this->_tokenResponse;
    }


    /**
     * Sets the token factory object.
     * 
     * @param EntityFactoryInterface $factory
     */
    public function setTokenFactory(EntityFactoryInterface $factory)
    {
        $this->_tokenFactory = $factory;
    }


    /**
     * Returns the token factory object.
     * 
     * @return EntityFactoryInterface
     */
    public function getTokenFactory()
    {
        if (! $this->_tokenFactory) {
            $this->_tokenFactory = new EntityFactory('\InoOicServer\OpenIdConnect\Entity\Token');
        }
        
        return $this->_tokenFactory;
    }


    /**
     * Sets the client authentication manager.
     * 
     * @param Client\Authentication\Manager $clientAuthenticationManager
     */
    public function setClientAuthenticationManager(Client\Authentication\Manager $clientAuthenticationManager)
    {
        $this->_clientAuthenticationManager = $clientAuthenticationManager;
    }


    /**
     * Returns the client authentication manager.
     * 
     * @param boolean $throwException
     * @throws GeneralException\MissingDependencyException
     * @return Client\Authentication\Manager 
     */
    public function getClientAuthenticationManager($throwException = false)
    {
        if (! ($this->_clientAuthenticationManager instanceof Client\Authentication\Manager) && $throwException) {
            throw new GeneralException\MissingDependencyException('client authentication manager');
        }
        
        return $this->_clientAuthenticationManager;
    }


    /**
     * @return IdTokenFactory
     */
    public function getIdTokenFactory()
    {
        if (! $this->_idTokenFactory instanceof IdTokenFactory) {
            $this->_idTokenFactory = new IdTokenFactory();
        }
        
        return $this->_idTokenFactory;
    }


    /**
     * @param IdTokenFactory $idTokenFactory
     */
    public function setIdTokenFactory(IdTokenFactory $idTokenFactory)
    {
        $this->_idTokenFactory = $idTokenFactory;
    }


    /**
     * @return ServerInfo
     */
    public function getServerInfo()
    {
        return $this->_serverInfo;
    }


    /**
     * @param ServerInfo $serverInfo
     */
    public function setServerInfo(ServerInfo $serverInfo)
    {
        $this->_serverInfo = $serverInfo;
    }


    /**
     * Dispatches the request and returns the response.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
    public function dispatch()
    {
        $request = $this->getTokenRequest(true);
        
        /*
         * Validate request
         */
        if (! $request->isValid()) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_REQUEST, sprintf("Reasons: %s", implode(', ', $request->getInvalidReasons())));
        }
        
        /*
         * Validate client
         */
        $clientRegistry = $this->getClientRegistry(true);
        
        $client = $clientRegistry->getClientById($request->getClientId());
        if (! $client) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_CLIENT, sprintf("Client with ID '%s' not found in registry", $request->getClientId()));
        }
        
        /*
         * Authenticate client
         */
        $clientAuthenticationManager = $this->getClientAuthenticationManager(true);
        $result = $clientAuthenticationManager->authenticate($request, $client);
        if (! $result->isAuthenticated()) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_CLIENT, sprintf("Client authentication failure with method '%s': %s", $client->getAuthenticationInfo()
                ->getMethod(), $result->getNotAuthenticatedReason()));
        }
        
        /*
         * Retrieve and validate the authorization code.
         */
        $sessionManager = $this->getSessionManager(true);
        
        $authorizationCode = $sessionManager->getAuthorizationCode($request->getCode());
        if (! $authorizationCode) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT, 'No such authorization code');
        }
        
        if ($authorizationCode->isExpired()) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT_EXPIRED, 'Expired authorization code');
        }
        
        /*
         * Retrieve session for the provided authorization code.
         */
        $session = $sessionManager->getSessionForAuthorizationCode($authorizationCode);
        if (! $session) {
            return $this->_errorResponse(Response\Token::ERROR_INVALID_GRANT_NO_SESSION, 'No session associated with the authorization code');
        }
        
        $user = $sessionManager->getUserFromSession($session);
        
        $idToken = $this->getIdTokenFactory()->createIdToken($user, $client, $session, $this->getServerInfo());
        $encodedIdToken = $this->_encodeIdToken($idToken, $client);
        /*
         * Create the access token object.
         */
        $accessToken = $sessionManager->createAccessToken($session, $client);
        
        $accessTokenEntity = $this->_createTokenEntity($accessToken, $encodedIdToken);
        // _dump($accessTokenEntity);
        return $this->_validResponse($accessTokenEntity);
    }


    protected function _createTokenEntity(AccessToken $accessToken, $idToken)
    {
        $tokenFactory = $this->getTokenFactory();
        if (! ($tokenFactory instanceof EntityFactoryInterface)) {
            throw new GeneralException\MissingDependencyException('token factory');
        }
        
        return $tokenFactory->createEntity(array(
            Entity\Token::FIELD_ACCESS_TOKEN => $accessToken->getToken(),
            Entity\Token::FIELD_TOKEN_TYPE => $accessToken->getType(),
            Entity\Token::FIELD_EXPIRES_IN => $accessToken->expiresIn(),
            Entity\Token::FIELD_REFRESH_TOKEN => 'not set',
            Entity\Token::FIELD_ID_TOKEN => $idToken
        ));
    }


    protected function _encodeIdToken(IdToken $idToken, Client\Client $client)
    {
        $claims = $idToken->getClaims();
        $header = $idToken->getHeader();
        
        $headerString = base64_encode(\Zend\Json\Json::encode($header));
        $payloadString = base64_encode(\Zend\Json\Json::encode($claims));
        
        $clientAuthOptions = $client->getAuthenticationInfo()->getOptions();
        $secret = $clientAuthOptions['secret'];
        
        $signingInput = $headerString . '.' . $payloadString;
        $signature = hash_hmac('sha256', $signingInput, $secret, true);
        $signatureString = base64_encode($signature);
        
        $encodedIdToken = sprintf("%s.%s.%s", $headerString, $payloadString, $signatureString);
        
        return $encodedIdToken;
    }


    /**
     * Returns a valid response based on the provided token data.
     * 
     * @param Entity\Token $entityToken
     * @throws GeneralException\MissingDependencyException
     * @return Response\Token
     */
    protected function _validResponse(Entity\Token $entityToken)
    {
        $response = $this->getTokenResponse(true);
        
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
    protected function _errorResponse($message, $description = NULL)
    {
        $response = $this->getTokenResponse(true);
        
        $response->setError($message, $description);
        
        return $response;
    }
}