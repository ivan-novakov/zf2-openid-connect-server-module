<?php

namespace PhpIdServer\User;

use PhpIdServer\Entity\Entity;


class User extends Entity
{

    const FIELD_ID = 'id';

    const FIELD_FIRST_NAME = 'first_name';

    const FIELD_SURNAME = 'surname';

    const FIELD_EMAIL = 'email';

    protected $_fields = array(
        self::FIELD_ID, 
        self::FIELD_FIRST_NAME, 
        self::FIELD_SURNAME, 
        self::FIELD_EMAIL
    );


    public function getId ()
    {
        return $this->getValue(self::FIELD_ID);
    }
}