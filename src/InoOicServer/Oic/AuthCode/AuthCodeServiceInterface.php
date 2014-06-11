<?php

namespace InoOicServer\Oic\AuthCode;

use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\Client\Client;


interface AuthCodeServiceInterface
{


    /**
     * Creates an authorization code for the provided session, client and scope.
     * 
     * @param Session $session
     * @param Client $client
     * @param string $scope
     */
    public function createAuthCode(Session $session, Client $client, $scope = null);


    /**
     * Saves the authorization code to the storage.
     * 
     * @param AuthCode $authCode
     */
    public function saveAuthCode(AuthCode $authCode);


    /**
     * Returns the authorization code entity which has the provided hash.
     * 
     * @param string $codeHash
     * @return AuthCode|null
     */
    public function fetchAuthCode($code);


    /**
     * Returns the authorization code linked to the provided session, client and scope.
     * 
     * @param Session $session
     * @param Client $client
     * @param string $scope
     * @return AuthCode
     */
    public function fetchAuthCodeBySession(Session $session, Client $client, $scope = null);


    /**
     * Deletes the authorization code instance.
     *  
     * @param AuthCode $authCode
     */
    public function deleteAuthCode(AuthCode $authCode);
}