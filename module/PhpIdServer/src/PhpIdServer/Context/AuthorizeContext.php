<?php
namespace PhpIdServer\Context;
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
     * @var OpenIdConnect\Request
     */
    protected $_request = NULL;


    /**
     * Sets to OIC request object.
     * 
     * @param OpenIdConnect\Request $request
     */
    public function setRequest (OpenIdConnect\Request $request)
    {
        $this->_request = $request;
    }


    /**
     * Returns the OIC request object.
     * 
     * @return OpenIdConnect\Request
     */
    public function getRequest ()
    {
        return $this->_request;
    }
}