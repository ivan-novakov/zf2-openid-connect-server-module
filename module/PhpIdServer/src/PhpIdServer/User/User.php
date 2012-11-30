<?php

namespace PhpIdServer\User;

use PhpIdServer\Entity\Entity;


/**
 * The user entity.
 * 
 * @method string getId()
 * @method string getName()
 * @method string getGivenName()
 * @method string getFamilyName()
 * @method string getNickname()
 * @method string getEmail()
 * 
 *
 */
class User extends Entity implements UserInterface
{

    const FIELD_ID = 'id';

    const FIELD_NAME = 'name';

    const FIELD_GIVEN_NAME = 'given_name';

    const FIELD_FAMILY_NAME = 'family_name';

    const FIELD_NICKNAME = 'nickname';

    const FIELD_EMAIL = 'email';

    protected $_fields = array(
        self::FIELD_ID, 
        self::FIELD_NAME, 
        self::FIELD_GIVEN_NAME, 
        self::FIELD_FAMILY_NAME, 
        self::FIELD_NICKNAME, 
        self::FIELD_EMAIL
    );
}