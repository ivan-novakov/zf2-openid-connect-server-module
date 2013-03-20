<?php

namespace PhpIdServerTest\Client;

use PhpIdServer\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{


    public function testGetAuthenticationInfo ()
    {
        $client = new Client\Client($this->_getClientData());
        
        $authenticationInfo = $client->getAuthenticationInfo();
        
        $this->assertInstanceOf('\PhpIdServer\Client\Authentication\Info', $authenticationInfo);
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


    public function testGetUserAuthenticationHandler ()
    {
        $client = new Client\Client($this->_getClientData());
        $this->assertSame('dummy', $client->getUserAuthenticationHandler());
    }


    protected function _getClientData ()
    {
        return array(
            'id' => 'myClientId', 
            'type' => 'public', 
            'authentication' => array(
                'method' => 'secret', 
                'options' => array(
                    'secret' => 'xxx'
                )
            ), 
            'user_authentication_handler' => 'dummy'
        );
    }
}