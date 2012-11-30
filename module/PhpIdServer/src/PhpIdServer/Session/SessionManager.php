<?php

namespace PhpIdServer\Session;

use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\User\UserInterface;
use PhpIdServer\User\Serializer;
use PhpIdServer\Client\Client;
use PhpIdServer\Authentication;


class SessionManager
{

    /**
     * The session storage.
     * 
     * @var Storage\StorageInterface
     */
    protected $_storage = NULL;

    /**
     * The user serializer object.
     * 
     * @var Serializer\SerializerInterface
     */
    protected $_userSerializer = NULL;

    /**
     * The session ID generator object.
     * 
     * @var IdGenerator\IdGeneratorInterface
     */
    protected $_sessionIdGenerator = NULL;

    /**
     * Token generator oibject.
     * 
     * @var Hash\Generator\GeneratorInterface
     */
    protected $_tokenGenerator = NULL;


    /**
     * Extracts user data from the session and returns the user object.
     * 
     * @param Session $session
     * @throws GeneralException\MissingDependencyException
     * @return UserInterface
     */
    public function getUserFromSession (Session $session)
    {
        $serializer = $this->getUserSerializer();
        if (! $serializer) {
            throw new GeneralException\MissingDependencyException('user serializer');
        }
        
        return $serializer->unserialize($session->getUserData());
    }


    /**
     * Creates a new session for the provided user and saves it in the storage.
     * 
     * @param UserInterface $user
     * @param Authentication\Info $authenticationInfo
     * @throws Exception\MissingComponentException
     * @return Session
     */
    public function createSession (UserInterface $user, Authentication\Info $authenticationInfo)
    {
        $sessionIdGenerator = $this->getSessionIdGenerator();
        if (! $sessionIdGenerator) {
            throw new GeneralException\MissingDependencyException('session ID generator');
        }
        
        $serializer = $this->getUserSerializer();
        if (! $serializer) {
            throw new GeneralException\MissingDependencyException('user serializer');
        }
        
        $storage = $this->_getStorageWithCheck();
        
        $sessionId = $sessionIdGenerator->generateId(array(
            $user->getId()
        ));
        
        $serializedUserData = $serializer->serialize($user);
        
        $now = new \DateTime('now');
        $session = new Session(array(
            Session::FIELD_ID => $sessionId, 
            Session::FIELD_USER_ID => $user->getId(), 
            Session::FIELD_CREATE_TIME => $now, 
            Session::FIELD_MODIFY_TIME => $now, 
            Session::FIELD_AUTHENTICATION_TIME => $authenticationInfo->getTime(), 
            Session::FIELD_EXPIRATION_TIME => new \DateTime('tomorrow'), 
            Session::FIELD_AUTHENTICATION_METHOD => $authenticationInfo->getMethod(), 
            Session::FIELD_USER_DATA => $serializedUserData
        ));
        
        $storage->saveSession($session);
        
        return $session;
    }


    public function updateSession (Session $session)
    {}


    /**
     * Returns the session associated with the authorization code.
     * 
     * @param AuthorizationCode $authorizationCode
     * @return Session
     */
    public function getSessionForAuthorizationCode (AuthorizationCode $authorizationCode)
    {
        return $this->_getStorageWithCheck()
            ->loadSession($authorizationCode->getSessionId());
    }


    /**
     * Returns the corresponding session for the provided access token.
     * 
     * @param AccessToken $accessToken
     * @return Session
     */
    public function getSessionByAccessToken (AccessToken $accessToken)
    {
        return $this->_getStorageWithCheck()
            ->loadSession($accessToken->getSessionId());
    }


    /**
     * Creates new authorization code for the provided session, bound to the provided client.
     * 
     * @param Session $session
     * @param Client $client
     * @throws Exception\MissingComponentException
     * @return AuthorizationCode
     */
    public function createAuthorizationCode (Session $session, Client $client)
    {
        $tokenGenerator = $this->getTokenGenerator();
        if (! $tokenGenerator) {
            throw new GeneralException\MissingDependencyException('token generator');
        }
        
        $storage = $this->_getStorageWithCheck();
        
        $code = $tokenGenerator->generateAuthorizationCode($session, $client);
        
        $authorizationCode = new AuthorizationCode(array(
            AuthorizationCode::FIELD_CODE => $code, 
            AuthorizationCode::FIELD_SESSION_ID => $session->getId(), 
            AuthorizationCode::FIELD_ISSUE_TIME => new \DateTime('now'), 
            // FIXME - set 5 min from config
            AuthorizationCode::FIELD_EXPIRATION_TIME => new \DateTime('tomorrow'), 
            AuthorizationCode::FIELD_CLIENT_ID => $client->getId(), 
            AuthorizationCode::FIELD_SCOPE => 'openid'
        ));
        
        $storage->saveAuthorizationCode($authorizationCode);
        
        return $authorizationCode;
    }


    /**
     * Returns the authorization code object for the provided code or NULL if not found.
     * 
     * @param string $code
     * @throws GeneralException\MissingDependencyException
     * @return AuthorizationCode|NULL
     */
    public function getAuthorizationCode ($code)
    {
        return $this->_getStorageWithCheck()
            ->loadAuthorizationCode($code);
    }


    /**
     * Deactivates the authentication code - it will not be possible to acquire the corresponding session anymore.
     * 
     * @param unknown_type $code
     */
    public function deactivateAuthorizationCode (AuthorizationCode $authorizationCode)
    {
        $this->_getStorageWithCheck()
            ->deleteAuthorizationCode($authorizationCode);
    }


    public function createAccessToken (Session $session, Client $client)
    {
        $storage = $this->_getStorageWithCheck();
        $generator = $this->_getTokenGeneratorWithCheck();
        
        $token = $generator->generateAccessToken($session, $client);
        
        $accessToken = new AccessToken(array(
            AccessToken::FIELD_TOKEN => $token, 
            AccessToken::FIELD_SESSION_ID => $session->getId(), 
            AccessToken::FIELD_CLIENT_ID => $client->getId(), 
            AccessToken::FIELD_ISSUE_TIME => new \DateTime('now'), 
            // FIXME set from config
            AccessToken::FIELD_EXPIRATION_TIME => new \DateTime('tomorrow'), 
            AccessToken::FIELD_TYPE => AccessToken::TYPE_BEARER, 
            AccessToken::FIELD_SCOPE => 'openid'
        ));
        
        $storage->saveAccessToken($accessToken);
        
        return $accessToken;
    }


    /**
     * Returns the access token object with the provided code.
     * 
     * @param string $token
     * @return AccessToken
     */
    public function getAccessToken ($token)
    {
        return $this->_getStorageWithCheck()
            ->loadAccessToken($token);
    }


    public function createRefreshToken ()
    {}


    public function getRefreshToken ($token)
    {}


    /**
     * Sets the session storage object.
     * 
     * @param Storage\StorageInterface $storage
     */
    public function setStorage (Storage\StorageInterface $storage)
    {
        $this->_storage = $storage;
    }


    /**
     * Returns the session storage object.
     * 
     * @return Storage\StorageInterface
     */
    public function getStorage ()
    {
        return $this->_storage;
    }


    /**
     * Sets the session ID generator object.
     * 
     * @param IdGenerator\IdGeneratorInterface $idGenerator
     */
    public function setSessionIdGenerator (IdGenerator\IdGeneratorInterface $idGenerator)
    {
        $this->_sessionIdGenerator = $idGenerator;
    }


    /**
     * Returns the session ID generator object.
     * 
     * @return IdGenerator\IdGeneratorInterface
     */
    public function getSessionIdGenerator ()
    {
        return $this->_sessionIdGenerator;
    }


    /**
     * Sets the token generator object.
     * 
     * @param Hash\Generator\GeneratorInterface
     */
    public function setTokenGenerator (Hash\Generator\GeneratorInterface $generator)
    {
        $this->_tokenGenerator = $generator;
    }


    /**
     * Return the token generator object.
     * 
     * @return Hash\Generator\GeneratorInterface
     */
    public function getTokenGenerator ()
    {
        return $this->_tokenGenerator;
    }


    /**
     * Sets the user serializer object.
     * 
     * @param Serializer\SerializerInterface $serializer
     */
    public function setUserSerializer (Serializer\SerializerInterface $serializer)
    {
        $this->_userSerializer = $serializer;
    }


    /**
     * Returns the user serializer object.
     * 
     * @return Serializer\SerializerInterface
     */
    public function getUserSerializer ()
    {
        return $this->_userSerializer;
    }


    /**
     * Returns the storage object and throws exception if not set.
     * 
     * @throws Exception\MissingComponentException
     * @return Storage\StorageInterface
     */
    protected function _getStorageWithCheck ()
    {
        $storage = $this->getStorage();
        if (! $storage) {
            throw new GeneralException\MissingDependencyException('session storage');
        }
        
        return $storage;
    }


    /**
     * Returns the token generator object or throws exception if not set.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return Hash\Generator\GeneratorInterface
     */
    protected function _getTokenGeneratorWithCheck ()
    {
        $generator = $this->getTokenGenerator();
        if (! $generator) {
            throw new GeneralException\MissingDependencyException('token generator');
        }
        
        return $generator;
    }
}