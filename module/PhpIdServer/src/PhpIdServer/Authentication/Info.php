<?php

namespace PhpIdServer\Authentication;

use PhpIdServer\Entity\Entity;


class Info extends Entity
{

    const FIELD_HANDLER = 'handler';

    const FIELD_TIME = 'time';


    public function getHandler ()
    {
        return $this->getValue(self::FIELD_HANDLER);
    }


    public function getTime ()
    {
        return $this->getValue(self::FIELD_TIME);
    }
}