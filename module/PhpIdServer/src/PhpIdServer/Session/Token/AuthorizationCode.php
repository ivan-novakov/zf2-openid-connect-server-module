<?php

namespace PhpIdServer\Session\Token;


/**
 * Authorization code entity.
 *
 * @method string getCode()
 * @method DateTime getIssueTime()
 * @method DateTime getExpirationTime()
 * @method string getSessionId()
 * @method string getClientId()
 * @method string getScope()
 * 
 */
class AuthorizationCode extends AbstractToken
{

    const FIELD_CODE = 'code';

    const FIELD_SESSION_ID = 'session_id';

    const FIELD_ISSUE_TIME = 'issue_time';

    const FIELD_EXPIRATION_TIME = 'expiration_time';

    const FIELD_CLIENT_ID = 'client_id';

    const FIELD_SCOPE = 'scope';

    protected $_fields = array(
        self::FIELD_CODE, 
        self::FIELD_SESSION_ID, 
        self::FIELD_ISSUE_TIME, 
        self::FIELD_EXPIRATION_TIME, 
        self::FIELD_CLIENT_ID, 
        self::FIELD_SCOPE
    );

    protected $_idField = self::FIELD_CODE;


    public function toArray ()
    {
        $arrayData = parent::toArray();
        
        return $this->_arrayDateObjectToTimeString($arrayData, array(
            static::FIELD_ISSUE_TIME, 
            static::FIELD_EXPIRATION_TIME
        ));
    }
}