<?php

namespace InoOicServer\OpenIdConnect\IdToken;

use InoOicServer\User\UserInterface;
use InoOicServer\Client\Client;
use InoOicServer\Session\Session;
use InoOicServer\Server\ServerInfo;


class IdTokenFactory
{


    public function createIdToken(UserInterface $user, Client $client, Session $session, ServerInfo $serverInfo)
    {
        $issuedAtTime = time();
        $jweConfig = $serverInfo->getJwe();
        
        $header = array(
            'typ' => 'JWT',
            'alg' => $jweConfig['alg']
        );
        
        $idToken = new IdToken();
        $idToken->setHeader($header);
        $idToken->setIssuer($serverInfo->getBaseUri());
        $idToken->setSubject($user->getId());
        $idToken->setAudience($client->getId());
        $idToken->setExpires($issuedAtTime + 300);
        $idToken->setIssuedAt($issuedAtTime);
        $idToken->setNonce($session->getNonce());
        
        return $idToken;
    }
}