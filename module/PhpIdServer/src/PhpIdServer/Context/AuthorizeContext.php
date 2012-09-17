<?php

namespace PhpIdServer\Context;

use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect\Request\Authorize;
use PhpIdServer\Authentication;
use PhpIdServer\User\User;


class AuthorizeContext extends AbstractContext
{

    const STATE_INITIAL = 'initial';

    const STATE_REQUEST_VALIDATED = 'request-validated';

    const STATE_USER_AUTHENTICATED = 'user-authenticated';

    const STATE_USER_CONSENT_APPROVED = 'user-consent-approved';

    protected $_states = array(
        self::STATE_INITIAL, 
        self::STATE_REQUEST_VALIDATED, 
        self::STATE_USER_AUTHENTICATED, 
        self::STATE_USER_CONSENT_APPROVED
    );

    protected $_finalState = self::STATE_USER_CONSENT_APPROVED;

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
     * @var User
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


    public function isUserAuthenticated ()
    {
        return ($this->_authenticationInfo instanceof Authentication\Info);
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
     * @param User $user
     */
    public function setUser (User $user)
    {
        $this->_user = $user;
    }


    /**
     * Returns the user object holding user's identity.
     * 
     * @return User
     */
    public function getUser ()
    {
        return $this->_user;
    }
}