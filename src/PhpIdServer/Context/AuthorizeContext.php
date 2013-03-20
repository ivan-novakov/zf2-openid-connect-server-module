<?php

namespace PhpIdServer\Context;

use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect\Request\Authorize;
use PhpIdServer\Authentication;
use PhpIdServer\User\UserInterface;


class AuthorizeContext extends AbstractContext
{

    /**
     * The OIC request object.
     * 
     * @var OpenIdConnect\Request\AbstractRequest
     */
    protected $_request = NULL;

    /**
     * Client object.
     * 
     * @var Client
     */
    protected $_client = NULL;

    /**
     * Authentication info object.
     * 
     * @var Authentication\Info
     */
    protected $_authenticationInfo = NULL;

    /**
     * User object.
     * 
     * @var UserInterface
     */
    protected $_user = NULL;


    /**
     * Sets to OIC request object.
     * 
     * @param mixed $request
     */
    public function setRequest ($request)
    {
        $this->_request = $request;
    }


    /**
     * Returns the OIC request object.
     * 
     * @return mixed
     */
    public function getRequest ()
    {
        return $this->_request;
    }


    /**
     * Sets the client object.
     * 
     * @param Client $client
     */
    public function setClient (Client $client)
    {
        $this->_client = $client;
    }


    /**
     * Returns the client object.
     * 
     * @return Client
     */
    public function getClient ()
    {
        return $this->_client;
    }


    /**
     * Returns true, if the user has been authenticated.
     * 
     * @return boolean
     */
    public function isUserAuthenticated ()
    {
        return ($this->_authenticationInfo instanceof Authentication\Info && $this->_authenticationInfo->isAuthenticated());
    }


    /**
     * Sets the authentication info.
     * 
     * @param Authentication\Info $info
     */
    public function setAuthenticationInfo (Authentication\Info $info)
    {
        $this->_authenticationInfo = $info;
    }


    /**
     * Returns the authentication info.
     * 
     * @return Authentication\Info
     */
    public function getAuthenticationInfo ()
    {
        return $this->_authenticationInfo;
    }


    /**
     * Sets the user object holding user's identity.
     * 
     * @param UserInterface $user
     */
    public function setUser (UserInterface $user)
    {
        $this->_user = $user;
    }


    /**
     * Returns the user object holding user's identity.
     * 
     * @return UserInterface
     */
    public function getUser ()
    {
        return $this->_user;
    }
}