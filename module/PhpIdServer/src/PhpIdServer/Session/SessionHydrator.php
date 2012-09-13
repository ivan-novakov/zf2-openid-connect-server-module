<?php

namespace PhpIdServer\Session;

use PhpIdServer\Entity\AbstractHydrator;


class SessionHydrator extends AbstractHydrator
{


    /**
     * Returns data extracted from the session object.
     * 
     * @param Session $session
     * @return array
     */
    public function extractData (Session $session)
    {
        return $this->extract($session);
    }


    /**
     * Loads data to the provided object.
     * 
     * @param array $data
     * @param Session $session
     */
    public function hydrateObject (Array $data, Session $session)
    {
        $this->hydrate($data, $session);
    }
}