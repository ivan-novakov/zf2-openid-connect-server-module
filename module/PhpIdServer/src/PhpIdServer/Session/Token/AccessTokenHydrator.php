<?php

namespace PhpIdServer\Session\Token;

use PhpIdServer\Session\Token\AccessToken;


class AccessTokenHydrator extends \Zend\Stdlib\Hydrator\ArraySerializable
{


    /**
     * Extracts values from the provided object.
     *
     * @param AccessToken $code
     * @return array
     */
    public function extractData (AccessToken $code)
    {
        return $this->extract($code);
    }


    /**
     * Loads the provided object with data.
     *
     * @param array $data
     * @param AccessToken $code
     */
    public function hydrateObject (Array $data, AccessTOken $code)
    {
        return $this->hydrate($data, $code);
    }
}