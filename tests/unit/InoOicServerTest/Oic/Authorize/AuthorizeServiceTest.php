<?php

namespace InoOicServerTest\Oic\Authorize;

use InoOicServer\Oic\Client\Client;
use InoOicServer\Oic\AuthCode\AuthCode;
use InoOicServer\Oic\Session\Session;
use InoOicServer\Oic\AuthSession\AuthSession;
use InoOicServer\Oic\Authorize\AuthorizeRequest;
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
        
        $this->service = new AuthorizeService($clientService, $contextService, $authSessionService, $sessionService, $authCodeService);
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


    public function testFetchAuthSessionFromRequestWithExistingAuthSession()
    {
        $authSessionId = '123';
        
        $request = new AuthorizeRequest();
        $request->setAuthenticationSessionId($authSessionId);
        
        $authSession = new AuthSession();
        
        $authSessionService = $this->createAuthSessionServiceMock();
        $authSessionService->expects($this->once())
            ->method('fetchSession')
            ->with($authSessionId)
            ->will($this->returnValue($authSession));
        $this->service->setAuthSessionService($authSessionService);
        
        $this->assertSame($authSession, $this->service->fetchAuthSessionFromRequest($request));
    }


    public function testFetchAuthSessionFromRequestWithNoAuthSessionId()
    {
        $request = new AuthorizeRequest();
        
        $this->assertNull($this->service->fetchAuthSessionFromRequest($request));
    }


    public function testFetchSessionFromRequest()
    {
        $sessionId = '456';
        
        $request = new AuthorizeRequest();
        $request->setSessionId($sessionId);
        
        $session = new Session();
        
        $sessionService = $this->createSessionServiceMock();
        $sessionService->expects($this->once())
            ->method('fetchSession')
            ->with($sessionId)
            ->will($this->returnValue($session));
        $this->service->setSessionService($sessionService);
        
        $this->assertSame($session, $this->service->fetchSessionFromRequest($request));
    }


    public function testFetchSessionFromRequestWithNoSessionId()
    {
        $request = new AuthorizeRequest();
        
        $this->assertNull($this->service->fetchSessionFromRequest($request));
    }


    public function testInitAuthCodeFromAuthSession()
    {
        $authSession = new AuthSession();
        $nonce = 'foo';
        $scope = 'bar';
        $session = new Session();
        $client = new Client();
        $authCode = new AuthCode();
        
        $request = new AuthorizeRequest();
        $request->setNonce($nonce);
        $request->setScope($scope);
        
        $sessionService = $this->createSessionServiceMock();
        $sessionService->expects($this->once())
            ->method('initSessionFromAuthSession')
            ->with($authSession, $nonce)
            ->will($this->returnValue($session));
        $this->service->setSessionService($sessionService);
        
        $authCodeService = $this->createAuthCodeServiceMock();
        $authCodeService->expects($this->once())
            ->method('initAuthCodeFromSession')
            ->with($session, $client, $scope)
            ->will($this->returnValue($authCode));
        $this->service->setAuthCodeService($authCodeService);
        
        $this->assertSame($authCode, $this->service->initAuthCodeFromAuthSession($authSession, $client, $request));
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


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAuthSessionServiceMock()
    {
        $authSessionService = $this->getMock('InoOicServer\Oic\AuthSession\AuthSessionServiceInterface');
        
        return $authSessionService;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSessionServiceMock()
    {
        $sessionService = $this->getMock('InoOicServer\Oic\Session\SessionServiceInterface');
        
        return $sessionService;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAuthCodeServiceMock()
    {
        $authCodeService = $this->getMock('InoOicServer\Oic\AuthCode\AuthCodeServiceInterface');
        
        return $authCodeService;
    }
}