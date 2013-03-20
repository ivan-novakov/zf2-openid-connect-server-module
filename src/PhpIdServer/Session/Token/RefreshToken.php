<?php

namespace PhpIdServer\Session\Token;


class RefreshToken extends AbstractToken
{

    const FIELD_TOKEN = 'token';

    const FIELD_ACCESS_TOKEN = 'access_token';

    const FIELD_ISSUE_TIME = 'issue_time';

    const FIELD_EXPIRATION_TIME = 'expiration_time';

    const FIELD_CLIENT_ID = 'client_id';

    protected $_fields = array(
        self::FIELD_TOKEN, 
        self::FIELD_ACCESS_TOKEN, 
        self::FIELD_ISSUE_TIME, 
        self::FIELD_EXPIRATION_TIME, 
        self::FIELD_CLIENT_ID
    );
}