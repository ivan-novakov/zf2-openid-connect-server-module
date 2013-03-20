<?php

namespace PhpIdServer\OpenIdConnect\Entity;

use PhpIdServer\Entity\Entity;


/**
 * Entity holding token response data.
 * 
 * @method string getAccessToken()
 * @method string getRefreshToken()
 * @method string getIdToken()
 * @method integer getExpiresIn()
 * @method string getTokenType()
 *
 */
class Token extends Entity
{

    const FIELD_ACCESS_TOKEN = 'access_token';

    const FIELD_REFRESH_TOKEN = 'refresh_token';

    const FIELD_ID_TOKEN = 'id_token';

    const FIELD_EXPIRES_IN = 'expires_in';

    const FIELD_TOKEN_TYPE = 'token_type';

    protected $_fields = array(
        self::FIELD_ACCESS_TOKEN, 
        self::FIELD_REFRESH_TOKEN, 
        self::FIELD_ID_TOKEN, 
        self::FIELD_EXPIRES_IN, 
        self::FIELD_TOKEN_TYPE
    );

    protected $_data = array(
        self::FIELD_EXPIRES_IN => 3600, 
        self::FIELD_TOKEN_TYPE => 'Bearer'
    );
}