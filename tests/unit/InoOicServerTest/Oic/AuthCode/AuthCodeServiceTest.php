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
        
        $session = $this->getMock('InoOicServer\Oic\Session\Session');
        $client = $this->getMock('InoOicServer\Oic\Client\Client');
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
}