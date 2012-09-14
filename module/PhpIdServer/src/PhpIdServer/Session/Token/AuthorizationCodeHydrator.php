<?php

namespace PhpIdServer\Session\Token;

use PhpIdServer\Session\Token\AuthorizationCode;


class AuthorizationCodeHydrator extends \Zend\Stdlib\Hydrator\ArraySerializable
{


    /**
     * Extracts values from the provided object.
     *
     * @param AuthorizationCode $code
     * @return array
     */
    public function extractData (AuthorizationCode $code)
    {
        return $this->extract($code);
    }


    /**
     * Loads the provided object with data.
     *
     * @param array $data
     * @param AuthorizationCode $code
     */
    public function hydrateObject (Array $data, AuthorizationCode $code)
    {
        return $this->hydrate($data, $code);
    }
}