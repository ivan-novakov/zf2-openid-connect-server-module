<?php

namespace PhpIdServerTest\Session\IdGenerator;

use PhpIdServer\User\User;
use PhpIdServer\Client\Client;
use PhpIdServer\Session\IdGenerator;


class SimpleTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerateId ()
    {
        $client = new Client(array(
            Client::FIELD_ID => 'testclient'
        ));
        
        $user = new User(array(
            User::FIELD_ID => 'testuser'
        ));
        
        $time = time();
        $secretSalt = 'xxx';
        $generator = new IdGenerator\Simple(array(
            'secret_salt' => $secretSalt, 
            'time' => $time
        ));

        $expectedValue = md5($user->getId() . $client->getId() . $time . $secretSalt);

        $this->assertEquals($expectedValue, $generator->generateId($user, $client));
    }
}