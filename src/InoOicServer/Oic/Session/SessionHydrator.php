<?php

namespace InoOicServer\Oic\Session;

use Zend\Stdlib\Hydrator\ClassMethods;


class SessionHydrator extends ClassMethods
{


    public function hydrate(array $data, $session)
    {
        return parent::hydrate($data, $session);
    }


    public function extract($session)
    {
        return parent::extract($session);
    }
}