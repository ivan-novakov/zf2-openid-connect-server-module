<?php
namespace PhpIdServer\Context;
use PhpIdServer\Client\Client;
use PhpIdServer\OpenIdConnect;


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
     * Sets to OIC request object.
     * 
     * @param OpenIdConnect\Request\AbstractRequest $request
     */
    public function setRequest (OpenIdConnect\Request\AbstractRequest $request)
    {
        $this->_request = $request;
    }


    /**
     * Returns the OIC request object.
     * 
     * @return OpenIdConnect\Request\AbstractRequest
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
    
    
    public function isUserAuthenticated()
    {
        return false;
    }
}