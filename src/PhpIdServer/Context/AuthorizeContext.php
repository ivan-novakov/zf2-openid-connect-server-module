<?php

namespace PhpIdServer\Context;

use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect\Request\Authorize;
use PhpIdServer\Authentication;
use PhpIdServer\User\UserInterface;


class AuthorizeContext extends AbstractContext
{

    const STATUS_UNKNOWN = 10;

    const STATUS_INIT = 20;

    const STATUS_PRE_DISPATCHED = 30;

    const STATUS_AUTHENTICATED = 40;

    const STATUS_DISPATCHED = 100;

    /**
     * Status of the authorize request.
     * @var integer
     */
    protected $status = self::STATUS_UNKNOWN;

    /**
     * The OIC request object.
     * 
     * @var Authorize\Simple
     */
    protected $request = NULL;

    /**
     * Client object.
     * 
     * @var Client
     */
    protected $client = NULL;

    /**
     * Authentication info object.
     * 
     * @var Authentication\Info
     */
    protected $authenticationInfo = NULL;

    /**
     * User object.
     * 
     * @var UserInterface
     */
    protected $user = NULL;


    public function setStatus($status)
    {
        $this->status = $status;
    }


    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Sets to OIC request object.
     * 
     * @param Authorize\Simple $request
     */
    public function setRequest(Authorize\Simple $request)
    {
        $this->request = $request;
    }


    /**
     * Returns the OIC request object.
     * 
     * @return Authorize\Simple
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * Sets the client object.
     * 
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }


    /**
     * Returns the client object.
     * 
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }


    /**
     * Returns true, if the user has been authenticated.
     * 
     * @return boolean
     */
    public function isUserAuthenticated()
    {
        return ($this->authenticationInfo instanceof Authentication\Info && $this->authenticationInfo->isAuthenticated());
    }


    /**
     * Sets the authentication info.
     * 
     * @param Authentication\Info $info
     */
    public function setAuthenticationInfo(Authentication\Info $info)
    {
        $this->authenticationInfo = $info;
    }


    /**
     * Returns the authentication info.
     * 
     * @return Authentication\Info
     */
    public function getAuthenticationInfo()
    {
        return $this->authenticationInfo;
    }


    /**
     * Sets the user object holding user's identity.
     * 
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }


    /**
     * Returns the user object holding user's identity.
     * 
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }
}