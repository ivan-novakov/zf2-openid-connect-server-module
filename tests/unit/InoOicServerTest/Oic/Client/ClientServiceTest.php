<?php

namespace InoOicServerTest\Oic\Client;

use InoOicServer\Oic\Client\ClientService;


class ClientServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $service;


    public function setUp()
    {
        $this->service = new ClientService($this->createMapperMock());
    }


    public function testSetGetMapper()
    {
        $mapper = $this->createMapperMock();
        $this->service->setClientMapper($mapper);
        $this->assertSame($mapper, $this->service->getClientMapper());
    }


    public function testFetchClientWithRedirectUriMismatch()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Exception\RedirectUriMismatchException');
        
        $clientId = 'testclient';
        $redirectUri = 'http://redirect';
        
        $client = $this->createClientMock();
        $client->expects($this->once())
            ->method('hasRedirectUri')
            ->with($redirectUri)
            ->will($this->returnValue(false));
        
        $mapper = $this->createMapperMock();
        $mapper->expects($this->once())
            ->method('getClientById')
            ->with($clientId)
            ->will($this->returnValue($client));
        
        $this->service->setClientMapper($mapper);
        $this->service->fetchClient($clientId, $redirectUri);
    }
    
    /*
     * 
     */
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMapperMock()
    {
        $mapper = $this->getMock('InoOicServer\Oic\Client\Mapper\MapperInterface');
        
        return $mapper;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createClientMock()
    {
        $client = $this->getMock('InoOicServer\Oic\Client\Client');
        
        return $client;
    }
}