<?php

namespace InoOicServerTest\Session\IdGenerator;

use InoOicServer\User\User;
use InoOicServer\Client\Client;
use InoOicServer\Session\IdGenerator;


class SimpleTest extends \PHPUnit_Framework_TestCase
{


    public function testGenerateId ()
    {
        $time = time();
        $secretSalt = 'xxx';
        $generator = new IdGenerator\Simple(array(
            'secret_salt' => $secretSalt, 
            'time' => $time
        ));
        
        $generatedValue = $generator->generateId(array(
            'testuser'
        ));
        
        $this->assertInternalType('string', $generatedValue);
        $this->assertEquals(32, strlen($generatedValue));
    }
}