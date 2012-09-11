<?php

namespace PhpIdServer\Session;

use PhpIdServer\User\Serializer\SerializerInterface;
use PhpIdServer\Client\Client;
use PhpIdServer\User\User;
use PhpIdServer\Authentication;


class SessionManager
{

    protected $_storage = NULL;

    protected $_userSerializer = NULL;

    protected $_sessionIdGenerator = NULL;

    protected $_tokenGenerator = NULL;


    public function createSession (User $user, Client $client, Authentication\Info $authenticationInfo)
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
     * @param SerializerInterface $serializer
     */
    public function setUserSerializer (SerializerInterface $serializer)
    {
        $this->_userSerializer = $serializer;
    }
}