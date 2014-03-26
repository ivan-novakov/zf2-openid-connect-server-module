<?php

namespace InoOicServer\Oic\Session;


interface SessionFactoryInterface
{


    /**
     * Creates an emtpy session entity "prototype".
     * 
     * @return Session
     */
    public function createSession();
}
