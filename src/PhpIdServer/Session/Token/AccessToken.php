<?php

namespace PhpIdServer\Session\Token;


/**
 * Access token entity.
 * 
 * @method string getToken()
 * @method string getSessionId()
 * @method \DateTime getIssueTime()
 * @method \DateTime getExpirationTime()
 * @method string getClientId()
 * @method string getType()
 * @method string getScope()
 *
 */
class AccessToken extends AbstractToken
{

    const TYPE_BEARER = 'bearer';

    const FIELD_TOKEN = 'token';

    const FIELD_ISSUE_TIME = 'issue_time';

    const FIELD_EXPIRATION_TIME = 'expiration_time';

    const FIELD_CLIENT_ID = 'client_id';

    const FIELD_SESSION_ID = 'session_id';

    const FIELD_TYPE = 'type';

    const FIELD_SCOPE = 'scope';

    protected $_fields = array(
        self::FIELD_TOKEN, 
        self::FIELD_ISSUE_TIME, 
        self::FIELD_EXPIRATION_TIME, 
        self::FIELD_CLIENT_ID, 
        self::FIELD_SESSION_ID, 
        self::FIELD_TYPE, 
        self::FIELD_SCOPE
    );

    protected $_idField = self::FIELD_TOKEN;


    public function toArray ()
    {
        $arrayData = parent::toArray();
        
        return $this->_arrayDateObjectToTimeString($arrayData, array(
            static::FIELD_ISSUE_TIME, 
            static::FIELD_EXPIRATION_TIME
        ));
    }
}