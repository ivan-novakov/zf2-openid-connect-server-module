<?php

namespace InoOicServerTest\Client;

use InoOicServer\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{

    protected $clientId = 'abc';

    protected $authMethod = 'secret';

    protected $authOptions = array(
        'foo' => 'bar'
    );


    public function testGetAuthenticationInfo()
    {
        $client = new Client\Client($this->_getClientData());
        
        $authenticationInfo = $client->getAuthenticationInfo();
        
        $this->assertInstanceOf('InoOicServer\Client\Authentication\Info', $authenticationInfo);
        $this->assertSame($this->clientId, $authenticationInfo->getClientId());
        $this->assertSame($this->authMethod, $authenticationInfo->getMethod());
        $this->assertSame($this->authOptions, $authenticationInfo->getOptions());
    }


    public function testIncompleteAuthenticationInfoException()
    {
        $this->setExpectedException('InoOicServer\Client\Exception\IncompleteAuthenticationInfoException');
        
        $client = new Client\Client(array(
            'id' => 'myClientId',
            'type' => 'public'
        ));
        
        $authenticationInfo = $client->getAuthenticationInfo();
    }


    public function testGetUserAuthenticationHandler()
    {
        $client = new Client\Client($this->_getClientData());
        $this->assertSame('dummy', $client->getUserAuthenticationHandler());
    }


    protected function _getClientData()
    {
        return array(
            'id' => $this->clientId,
            'type' => 'public',
            'authentication' => array(
                'method' => $this->authMethod,
                'options' => $this->authOptions
            ),
            'user_authentication_handler' => 'dummy'
        );
    }
}