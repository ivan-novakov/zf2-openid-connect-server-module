<?php

namespace InoOicServer\Oic\Session;

use DateTime;
use InoOicServer\Oic\User;
use InoOicServer\Oic\Client\Client;


interface SessionFactoryInterface
{


    /**
     * Creates an emtpy session entity "prototype".
     * 
     * @return Session
     */
    public function createEmptySession();


    /**
     * Creates an new OIC session based on the user auth status and the client performing the request.
     * 
     * @param User\Authentication\Status $userAuthStatus
     * @return Session
     */
    public function createSession(User\Authentication\Status $userAuthStatus, DateTime $createTime = null);
}
