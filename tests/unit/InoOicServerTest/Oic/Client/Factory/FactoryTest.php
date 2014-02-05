<?php

namespace InoOicServerTest\Oic\Client\Factory;

use InoOicServer\Oic\Client\Factory\Factory;


class FactoryTest extends \PHPUnit_Framework_Testcase
{


    public function testCreateClient()
    {
        $factory = new Factory();
        
        $data = array(
            'id' => 'foo',
            'secret' => 'passwd',
            'redirectUris' => array(
                'https://bar1/',
                'https://bar2/'
            ),
            'userAuthenticationMethod' => 'dummy'
        );
        
        $client = $factory->createClient($data);
        
        $this->assertInstanceOf('InoOicServer\Oic\Client\Client', $client);
        $this->assertSame($data['id'], $client->getId());
        $this->assertSame($data['secret'], $client->getSecret());
        $this->assertSame($data['redirectUris'], $client->getRedirectUris());
        $this->assertSame($data['userAuthenticationMethod'], $client->getUserAuthenticationMethod());
    }
}