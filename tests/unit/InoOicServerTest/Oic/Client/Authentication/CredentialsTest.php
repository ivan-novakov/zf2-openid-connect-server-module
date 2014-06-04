<?php

namespace InoOicServerTest\Oic\Client\Authentication;

use InoOicServer\Oic\Client\Authentication\Credentials;


class CredentialsTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $id = 'testclient';
        $secret = 'testsecret';
        $type = 'client_secret_post';
        
        $credentials = new Credentials();
        $credentials->setClientId($id);
        $credentials->setClientSecret($secret);
        $credentials->setType($type);
        
        $this->assertSame($id, $credentials->getClientId());
        $this->assertSame($secret, $credentials->getClientSecret());
        $this->assertSame($type, $credentials->getType());
    }

}
