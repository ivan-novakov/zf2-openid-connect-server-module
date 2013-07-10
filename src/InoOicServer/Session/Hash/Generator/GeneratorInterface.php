<?php

namespace InoOicServer\Session\Hash\Generator;

use InoOicServer\Session\Token\AccessToken;
use InoOicServer\Client\Client;
use InoOicServer\Session\Session;


interface GeneratorInterface
{


    /**
     * Generates authorization code for the provided session and client.
     * 
     * @param Session $session
     * @param Client $client
     * @return string
     */
    public function generateAuthorizationCode (Session $session, Client $client);


    /**
     * Generates access token for the provided session and client.
     * 
     * @param Session $session
     * @param Client $client
     * @return string
     */
    public function generateAccessToken (Session $session, Client $client);


    /**
     * Generates refresh token for the provided access token and client.
     * 
     * @param AccessToken $accessToken
     * @param Client $client
     * @return string
     */
    public function generateRefreshToken (AccessToken $accessToken, Client $client);
}