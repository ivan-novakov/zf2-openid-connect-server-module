<?php

namespace PhpIdServer\Session;

use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\User\User;
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
     * Creates a new session for the provided user and saves it in the storage.
     * 
     * @param User $user
     * @param Authentication\Info $authenticationInfo
     * @throws Exception\MissingComponentException
     * @return Session
     */
    public function createSession (User $user, Authentication\Info $authenticationInfo)
    {
        $sessionIdGenerator = $this->getSessionIdGenerator();
        if (! $sessionIdGenerator) {
            throw new Exception\MissingComponentException('session ID generator');
        }
        
        $serializer = $this->getUserSerializer();
        if (! $serializer) {
            throw new Exception\MissingComponentException('user serializer');
        }
        
        $storage = $this->getStorage();
        if (! $storage) {
            throw new Exception\MissingComponentException('session storage');
        }
        
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
            throw new Exception\MissingComponentException('token generator');
        }
        
        $storage = $this->getStorage();
        if (! $storage) {
            throw new Exception\MissingComponentException('session storage');
        }
        
        $code = $tokenGenerator->generateAuthorizationCode($session, $client);
        
        $authorizationCode = new AuthorizationCode(array(
            AuthorizationCode::FIELD_CODE => $code, 
            AuthorizationCode::FIELD_SESSION_ID => $session->getId(), 
            AuthorizationCode::FIELD_ISSUE_TIME => new \DateTime('now'), 
            AuthorizationCode::FIELD_EXPIRATION_TIME => new \DateTime('tomorrow'), 
            AuthorizationCode::FIELD_CLIENT_ID => $client->getId(), 
            AuthorizationCode::FIELD_SCOPE => 'openid'
        ));
        
        $storage->saveAuthorizationCode($authorizationCode);
        
        return $authorizationCode;
    }


    public function getAuthorizationCode ($code)
    {}


    public function deactivateAuthorizationCode ($code)
    {}


    public function createAccessToken (Session $session, Client $client)
    {}


    public function getAccessToken ($tokenCode)
    {}


    public function createRefreshToken ()
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
}