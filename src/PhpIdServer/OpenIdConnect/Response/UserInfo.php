<?php

namespace PhpIdServer\OpenIdConnect\Response;

use PhpIdServer\General\Exception as GeneralException;
use PhpIdServer\User\UserInterface;


/**
 * Dispatches a userinfo request.
 */
class UserInfo extends AbstractTokenResponse
{
    
    /*
     * Error response code based on OIC and OAuth2:
     *   - http://openid.net/specs/openid-connect-messages-1_0.html#anchor10
     *   - http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-23#section-3.1
     */
    const ERROR_INVALID_REQUEST = 'invalid_request';

    const ERROR_INVALID_TOKEN = 'invalid_token';

    const ERROR_INVALID_TOKEN_NOT_FOUND = 'invalid_token|not_found';

    const ERROR_INVALID_TOKEN_EXPIRED = 'invalid_token|expired';

    const ERROR_INVALID_TOKEN_NO_SESSION = 'invalid_token|no_session';

    const ERROR_INVALID_TOKEN_NO_USER_DATA = 'invalid_token|no_user_data';

    const ERROR_INSUFFICIENT_SCOPE = 'insufficient_scope';

    const ERROR_INVALID_SCHEMA = 'invalid_schema';

    /**
     * The user data.
     *
     * @var array
     */
    protected $_userData = array();


    /**
     * Sets the user data.
     *
     * @param @array $userData
     */
    public function setUserData (array $userData)
    {
        $this->_userData = $userData;
    }


    /**
     * Returns the user data.
     *
     * @return array
     */
    public function getUserData ()
    {
        return $this->_userData;
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\OpenIdConnect\Response\AbstractTokenResponse::setError()
     */
    public function setError ($message, $description = NULL)
    {
        /*
         * The different error codes require different HTTP status codes:
         *   - http://tools.ietf.org/html/draft-ietf-oauth-v2-bearer-23#section-3.1
         */
        switch ($message) {
            case self::ERROR_INVALID_TOKEN:
                $this->_errorHttpStatusCode = 401;
                break;
            
            case self::ERROR_INSUFFICIENT_SCOPE:
                $this->_errorHttpStatusCode = 403;
                break;
            
            default:
                break;
        }
        
        parent::setError($message, $description);
    }


    /**
     * Returns the JSON encoded user info content.
     * 
     * @throws GeneralException\MissingDependencyException
     * @return string
     */
    protected function _createResponseContent ()
    {
        return $this->_jsonEncode($this->getUserData());
    }
}