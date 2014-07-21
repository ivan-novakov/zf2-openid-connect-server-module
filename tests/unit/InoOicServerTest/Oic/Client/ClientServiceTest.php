<?php
namespace InoOicServerTest\Oic\Client;

use InoOicServer\Oic\Client\ClientService;
use InoOicServer\Oic\Client\Client;
use InoOicServer\Oic\Authorize\AuthorizeRequest;

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

    public function testFetchClientFromAuthorizeRequestWithNotFound()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Exception\UnknownClientException', 'Unknown client');
        
        $clientId = 'testclient';
        
        $request = new AuthorizeRequest();
        $request->setClientId($clientId);
        
        $service = $this->getMockBuilder('InoOicServer\Oic\Client\ClientService')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'fetchClient'
        ))
            ->getMock();
        $service->expects($this->once())
            ->method('fetchClient')
            ->with($clientId)
            ->will($this->returnValue(null));
        
        $service->fetchClientFromAuthorizeRequest($request);
    }

    public function testFetchClientFromAuthorizeRequestWithMissingRedirectUri()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Exception\MissingRedirectUriException', 'No redirect URI in authorize request');
        
        $clientId = 'testclient';
        
        $client = new Client();
        $client->setId($clientId);
        
        $request = new AuthorizeRequest();
        $request->setClientId($clientId);
        
        $service = $this->getMockBuilder('InoOicServer\Oic\Client\ClientService')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'fetchClient'
        ))
            ->getMock();
        
        $service->expects($this->once())
            ->method('fetchClient')
            ->with($clientId)
            ->will($this->returnValue($client));
        
        $service->fetchClientFromAuthorizeRequest($request);
    }

    public function testFetchClientFromAuthorizeRequestWithRedirectUriMismatch()
    {
        $this->setExpectedException('InoOicServer\Oic\Client\Exception\RedirectUriMismatchException', 'Invalid redirect URI');
        
        $clientId = 'testclient';
        $redirectUri = 'https://redirect';
        
        $client = new Client();
        $client->setId($clientId);
        $client->setRedirectUris(array(
            $redirectUri
        ));
        
        $request = new AuthorizeRequest();
        $request->setClientId($clientId);
        $request->setRedirectUri('https://invalid/redirect');
        
        $service = $this->getMockBuilder('InoOicServer\Oic\Client\ClientService')
            ->disableOriginalConstructor()
            ->setMethods(array(
            'fetchClient'
        ))
            ->getMock();
        
        $service->expects($this->once())
            ->method('fetchClient')
            ->with($clientId)
            ->will($this->returnValue($client));
        
        $service->fetchClientFromAuthorizeRequest($request);
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