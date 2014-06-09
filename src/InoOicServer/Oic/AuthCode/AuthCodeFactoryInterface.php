<?php

namespace InoOicServer\Oic\AuthCode;

use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Client\Client;


interface AuthCodeFactoryInterface
{


    /**
     * @param Session $session
     * @param Client $client
     * @param integer $age
     * @param string $salt
     * @param string $scope
     * @return AuthCode
     */
    public function createAuthCode(Session $session, Client $client, $age, $salt, $scope = null);
}