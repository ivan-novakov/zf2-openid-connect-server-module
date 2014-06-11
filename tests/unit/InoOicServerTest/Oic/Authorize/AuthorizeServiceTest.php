<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Authorize\AuthorizeService;


class AuthorizeServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $service;


    public function setUp()
    {
        $contextService = $this->createContextServiceMock();
        $clientService = $this->createClientServiceMock();
        $authSessionService = $this->createAuthSessionServiceMock();
        $sessionService = $this->createSessionServiceMock();
        $authCodeService = $this->createAuthCodeServiceMock();
        
        $this->service = new AuthorizeService($clientService, $contextService, $authSessionService, $sessionService, 
            $authCodeService);
    }


    public function testSetContextService()
    {
        $contextService = $this->createContextServiceMock();
        $this->service->setContextService($contextService);
        $this->assertSame($contextService, $this->service->getContextService());
    }


    public function testSetClientService()
    {
        $clientService = $this->createClientServiceMock();
        $this->service->setClientService($clientService);
        $this->assertSame($clientService, $this->service->getClientService());
    }


    public function testSetAuthSessionService()
    {
        $authSessionService = $this->createAuthSessionServiceMock();
        $this->service->setAuthSessionService($authSessionService);
        $this->assertSame($authSessionService, $this->service->getAuthSessionService());
    }


    public function testSetSessionService()
    {
        $sessionService = $this->createSessionServiceMock();
        $this->service->setSessionService($sessionService);
        $this->assertSame($sessionService, $this->service->getSessionService());
    }


    public function testSetAuthCodeService()
    {
        $authCodeService = $this->createAuthCodeServiceMock();
        $this->service->setAuthCodeService($authCodeService);
        $this->assertSame($authCodeService, $this->service->getAuthCodeService());
    }
    
    /*
     * 
     */
    protected function createContextServiceMock()
    {
        $contextService = $this->getMock('InoOicServer\Oic\Authorize\Context\ContextServiceInterface');
        
        return $contextService;
    }


    protected function createClientServiceMock()
    {
        $clientService = $this->getMock('InoOicServer\Oic\Client\ClientServiceInterface');
        
        return $clientService;
    }


    protected function createAuthSessionServiceMock()
    {
        $authSessionService = $this->getMock('InoOicServer\Oic\AuthSession\AuthSessionServiceInterface');
        
        return $authSessionService;
    }


    protected function createSessionServiceMock()
    {
        $sessionService = $this->getMock('InoOicServer\Oic\Session\SessionServiceInterface');
        
        return $sessionService;
    }


    protected function createAuthCodeServiceMock()
    {
        $authCodeService = $this->getMock('InoOicServer\Oic\AuthCode\AuthCodeServiceInterface');
        
        return $authCodeService;
    }
}