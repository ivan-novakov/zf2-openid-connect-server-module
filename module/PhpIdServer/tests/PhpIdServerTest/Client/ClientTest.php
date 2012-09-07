<?php
namespace PhpIdServerTest\Client;
use PhpIdServer\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{


    public function testPopulate ()
    {
        $client = new Client\Client();
        $client->populate(array(
            'id' => 'myClientId', 
            'type' => 'public'
        ));
        
        $this->assertEquals('myClientId', $client->getId());
        $this->assertEquals('public', $client->getType());
    }


    public function testAuthenticationInfo ()
    {
        $client = new Client\Client(array(
            'id' => 'myClientId', 
            'type' => 'public', 
            'authentication' => array(
                'type' => Client\Authentication\Type::SECRET, 
                'options' => array(
                    'secret' => 'xxx'
                )
            )
        ));
        
        $authenticationInfo = $client->getAuthenticationInfo();
        
        $this->assertInstanceOf('\PhpIdServer\Client\Authentication\ClientInfo', $authenticationInfo);
    }


    public function testIncompleteAuthenticationInfoException ()
    {
        $this->setExpectedException('\PhpIdServer\Client\Exception\IncompleteAuthenticationInfoException');
        
        $client = new Client\Client(array(
            'id' => 'myClientId', 
            'type' => 'public'
        ));
        
        $authenticationInfo = $client->getAuthenticationInfo();
    }
}