<?php

namespace InoOicServerTest\Oic\AuthCode;

use InoOicServer\Oic\AuthCode\AuthCodeService;


class AuthCodeServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $service;


    public function setUp()
    {
        $this->service = new AuthCodeService($this->createAuthCodeMapperMock());
    }


    public function testSetMapper()
    {
        $mapper = $this->createAuthCodeMapperMock();
        $this->service->setAuthCodeMapper($mapper);
        $this->assertSame($mapper, $this->service->getAuthCodeMapper());
    }


    public function testSetFactory()
    {
        $factory = $this->createAuthCodeFactoryMock();
        $this->service->setAuthCodeFactory($factory);
        $this->assertSame($factory, $this->service->getAuthCodeFactory());
    }


    public function testGetImplicitFactory()
    {
        $this->assertInstanceOf('InoOicServer\Oic\AuthCode\AuthCodeFactoryInterface', $this->service->getAuthCodeFactory());
    }


    public function testCreateAuthCode()
    {
        $age = 120;
        $salt = 'secretsalt';
        $scope = 'foo bar';
        
        $session = $this->createSessionMock();
        $client = $this->createClientMock();
        $authCode = $this->createAuthCodeMock();
        
        $this->service->setOptions(array(
            'age' => $age,
            'salt' => $salt
        ));
        
        $factory = $this->createAuthCodeFactoryMock();
        $factory->expects($this->once())
            ->method('createAuthCode')
            ->with($session, $client, $age, $salt, $scope)
            ->will($this->returnValue($authCode));
        $this->service->setAuthCodeFactory($factory);
        
        $this->assertSame($authCode, $this->service->createAuthCode($session, $client, $scope));
    }


    public function testSaveAuthCode()
    {
        $authCode = $this->createAuthCodeMock();
        $mapper = $this->createAuthCodeMapperMock();
        $mapper->expects($this->once())
            ->method('save')
            ->with($authCode);
        $this->service->setAuthCodeMapper($mapper);
        
        $this->assertTrue($this->service->saveAuthCode($authCode));
    }


    public function testFetchAuthCode()
    {
        $code = 'testcode';
        $authCode = $this->createAuthCodeMock();
        
        $mapper = $this->createAuthCodeMapperMock();
        $mapper->expects($this->once())
            ->method('fetch')
            ->with($code)
            ->will($this->returnValue($authCode));
        $this->service->setAuthCodeMapper($mapper);
        
        $this->assertSame($authCode, $this->service->fetchAuthCode($code));
    }


    public function testFetchAuthCodeBySession()
    {
        $sessionId = 'testsession';
        $clientId = 'testclient';
        $scope = 'foo bar';
        
        $authCode = $this->createAuthCodeMock();
        
        $session = $this->createSessionMock();
        $session->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($sessionId));
        
        $client = $this->createClientMock();
        $client->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($clientId));
        
        $mapper = $this->createAuthCodeMapperMock();
        $mapper->expects($this->once())
            ->method('fetchBySession')
            ->with($sessionId, $clientId, $scope)
            ->will($this->returnValue($authCode));
        $this->service->setAuthCodeMapper($mapper);
        
        $this->assertSame($authCode, $this->service->fetchAuthCodeBySession($session, $client, $scope));
    }


    public function testDeleteAuthCode()
    {
        $code = 'testcode';
        $authCode = $this->createAuthCodeMock();
        $authCode->expects($this->once())
            ->method('getCode')
            ->will($this->returnValue($code));
        
        $mapper = $this->createAuthCodeMapperMock();
        $mapper->expects($this->once())
            ->method('delete')
            ->with($code);
        $this->service->setAuthCodeMapper($mapper);
        
        $this->assertTrue($this->service->deleteAuthCode($authCode));
    }
    
    /*
     * 
     */
    protected function createAuthCodeFactoryMock()
    {
        $factory = $this->getMock('InoOicServer\Oic\AuthCode\AuthCodeFactoryInterface');
        
        return $factory;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAuthCodeMapperMock()
    {
        $mapper = $this->getMock('InoOicServer\Oic\AuthCode\Mapper\MapperInterface');
        
        return $mapper;
    }


    protected function createAuthCodeMock()
    {
        $authCode = $this->getMock('InoOicServer\Oic\AuthCode\AuthCode');
        
        return $authCode;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSessionMock()
    {
        $session = $this->getMock('InoOicServer\Oic\Session\Session');
        
        return $session;
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