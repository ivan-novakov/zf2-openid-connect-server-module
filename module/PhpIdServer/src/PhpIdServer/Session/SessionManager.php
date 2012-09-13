<?php

namespace PhpIdServer\Session;

use PhpIdServer\User;
use PhpIdServer\User\User;
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
     * @var User\Serializer\SerializerInterface
     */
    protected $_userSerializer = NULL;

    /**
     * The session ID generator object.
     * 
     * @var IdGenerator\IdGeneratorInterface
     */
    protected $_sessionIdGenerator = NULL;

    protected $_tokenGenerator = NULL;


    public function createSession (User\User $user, Authentication\Info $authenticationInfo)
    {}


    public function updateSession (Session $session)
    {}


    public function createAuthorizationCode (Session $session, Client $client)
    {}


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
     * Sets the session ID generator object.
     * 
     * @param IdGenerator\IdGeneratorInterface $idGenerator
     */
    public function setSessionIdGenerator (IdGenerator\IdGeneratorInterface $idGenerator)
    {
        $this->_sessionIdGenerator = $idGenerator;
    }


    public function setTokenGenerator ()
    {}


    /**
     * Sets the user serializer object.
     * 
     * @param User\Serializer\SerializerInterface $serializer
     */
    public function setUserSerializer (User\Serializer\SerializerInterface $serializer)
    {
        $this->_userSerializer = $serializer;
    }
}