<?php

namespace PhpIdServer\Session\Hash\Generator;

use Zend\Crypt\Hash;
use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\Client\Client;
use PhpIdServer\Session\Session;


class Simple extends AbstractGenerator
{


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Hash\Generator\GeneratorInterface::generateAuthorizationCode()
     */
    public function generateAuthorizationCode (Session $session, Client $client)
    {
        $data = $session->getId() . $client->getId() . microtime(true) . 'authorization_code';
        
        return Hash::compute('sha1', $data);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Hash\Generator\GeneratorInterface::generateAccessToken()
     */
    public function generateAccessToken (Session $session, Client $client)
    {
        $data = $session->getId() . $client->getId() . microtime(true) . 'access_token';
        
        return Hash::compute('sha1', $data);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Hash\Generator\GeneratorInterface::generateRefreshToken()
     */
    public function generateRefreshToken (AccessToken $accessToken, Client $client)
    {
        $data = $accessToken->getToken() . $client->getId() . microtime(true) . 'refresh_token';
        
        return Hash::compute('sha1', $data);
    }
}