<?php

namespace InoOicServer\User;

use InoOicServer\Entity\Entity;


/**
 * The user entity.
 * 
 * @method string getId()
 * @method void setId(integer $id)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getGivenName()
 * @method void setGivenName(string $givenName)
 * @method string getFamilyName()
 * @method void setFamilyName(string $familyName)
 * @method string getNickname()
 * @method void setNickname(string $nickname)
 * @method string getEmail()
 * @method void setEmail(string $email)
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