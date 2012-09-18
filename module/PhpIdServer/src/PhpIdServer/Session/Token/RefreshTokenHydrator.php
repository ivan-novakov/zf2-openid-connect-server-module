<?php

namespace PhpIdServer\Session\Token;

use PhpIdServer\Session\Token\RefreshToken;


class RefreshTokenHydrator extends \Zend\Stdlib\Hydrator\ArraySerializable
{


    /**
     * Extracts values from the provided object.
     *
     * @param RefreshToken $code
     * @return array
     */
    public function extractData (RefreshToken $code)
    {
        return $this->extract($code);
    }


    /**
     * Loads the provided object with data.
     *
     * @param array $data
     * @param RefreshToken $code
     */
    public function hydrateObject (Array $data, RefreshTOken $code)
    {
        return $this->hydrate($data, $code);
    }
}