<?php

namespace InoOicServer\Oic\Session;


/**
 * OIC session factory.
 */
class SessionFactory implements SessionFactoryInterface
{


    /**
     * {@inhertidoc}
     * @see \InoOicServer\Oic\Session\SessionFactoryInterface::createSession()
     */
    public function createSession()
    {
        return new Session();
    }
}