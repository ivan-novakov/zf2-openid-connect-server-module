<?php

namespace InoOicServer\Session\Hash\Generator;

use Zend\Crypt\Hash;
use InoOicServer\Session\Token\AccessToken;
use InoOicServer\Client\Client;
use InoOicServer\Session\Session;


class Simple extends AbstractGenerator
{


    /**
     * (non-PHPdoc)
     * @see \InoOicServer\Session\Hash\Generator\GeneratorInterface::generateAuthorizationCode()
     */
    public function generateAuthorizationCode (Session $session, Client $client)
    {
        $data = $session->getId() . $client->getId() . microtime(true) . 'authorization_code';
        
        return Hash::compute('sha1', $data);
    }


    /**
     * (non-PHPdoc)
     * @see \InoOicServer\Session\Hash\Generator\GeneratorInterface::generateAccessToken()
     */
    public function generateAccessToken (Session $session, Client $client)
    {
        $data = $session->getId() . $client->getId() . microtime(true) . 'access_token';
        
        return Hash::compute('sha1', $data);
    }


    /**
     * (non-PHPdoc)
     * @see \InoOicServer\Session\Hash\Generator\GeneratorInterface::generateRefreshToken()
     */
    public function generateRefreshToken (AccessToken $accessToken, Client $client)
    {
        $data = $accessToken->getToken() . $client->getId() . microtime(true) . 'refresh_token';
        
        return Hash::compute('sha1', $data);
    }
}