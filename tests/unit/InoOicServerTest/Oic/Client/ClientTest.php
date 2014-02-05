<?php

namespace InoOicServerTest\Oic\Client;

use InoOicServer\Oic\Client\Client;


class ClientTest extends \PHPUnit_Framework_Testcase
{


    public function testSettersAndGetters()
    {
        $id = 'testclient';
        $secret = 'passwd';
        $redirectUris = array(
            'https://dummy1/',
            'https://dummy2'
        );
        $userAuthenticationMethod = 'basic';
        
        $client = new Client();
        $client->setId($id);
        $client->setSecret($secret);
        $client->setRedirectUris($redirectUris);
        $client->setUserAuthenticationMethod($userAuthenticationMethod);
        
        $this->assertSame($id, $client->getId());
        $this->assertSame($secret, $client->getSecret());
        $this->assertSame($redirectUris, $client->getRedirectUris());
        $this->assertSame($userAuthenticationMethod, $client->getUserAuthenticationMethod());
    }
}