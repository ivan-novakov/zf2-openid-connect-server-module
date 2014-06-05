<?php

namespace InoOicServerTest\Oic\AuthSession;

use InoOicServer\Oic\AuthSession\AuthSessionService;


class AuthSessionServiceTest extends \PHPUnit_Framework_TestCase
{

    protected $service;


    public function setUp()
    {
        $this->service = new AuthSessionService($this->createAuthSessionMapperMock());
    }


    public function testSetMapper()
    {
        $mapper = $this->createAuthSessionMapperMock();
        $this->service->setAuthSessionMapper($mapper);
        $this->assertSame($mapper, $this->service->getAuthSessionMapper());
    }


    public function testCreateSession()
    {
        $authStatus = $this->getMock('InoOicServer\Oic\User\Authentication\Status');
        $age = 120;
        $salt = 'secretsalt';
        $authSession = $this->createAuthSessionMock();
        
        $authSessionFactory = $this->getMock('InoOicServer\Oic\AuthSession\AuthSessionFactoryInterface');
        $authSessionFactory->expects($this->once())
            ->method('createAuthSession')
            ->with($authStatus, $age, $salt)
            ->will($this->returnValue($authSession));
        
        $this->service->setAuthSessionFactory($authSessionFactory);
        $this->service->setOptions(array(
            'age' => $age,
            'salt' => $salt
        ));
        
        $this->assertSame($authSession, $this->service->createSession($authStatus));
    }


    public function testSaveSession()
    {
        $authSession = $this->createAuthSessionMock();
        
        $mapper = $this->createAuthSessionMapperMock();
        $mapper->expects($this->once())
            ->method('save')
            ->with($authSession);
        
        $this->service->setAuthSessionMapper($mapper);
        $this->service->saveSession($authSession);
    }


    public function testFetchSession()
    {
        $id = '123asd';
        $authSession = $this->createAuthSessionMock();
        
        $mapper = $this->createAuthSessionMapperMock();
        $mapper->expects($this->once())
            ->method('fetch')
            ->with($id)
            ->will($this->returnValue($authSession));
        $this->service->setAuthSessionMapper($mapper);
        
        $this->assertSame($authSession, $this->service->fetchSession($id));
    }
    
    /*
     * 
     */
    protected function createAuthSessionMapperMock()
    {
        $mapper = $this->getMock('InoOicServer\Oic\AuthSession\Mapper\MapperInterface');
        
        return $mapper;
    }


    protected function createAuthSessionMock()
    {
        $authSession = $this->getMock('InoOicServer\Oic\AuthSession\AuthSession');
        
        return $authSession;
    }
}