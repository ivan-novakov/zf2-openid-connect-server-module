<?php
namespace InoOicServerTest\Oic\AuthSession;

use InoOicServer\Oic\AuthSession\AuthSessionService;
use InoOicServer\Oic\AuthSession\AuthSession;

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

    public function testSetFactory()
    {
        $factory = $this->createAuthSessionFactory();
        $this->service->setAuthSessionFactory($factory);
        $this->assertSame($factory, $this->service->getAuthSessionFactory());
    }

    public function testGetImplicitFactory()
    {
        $this->assertInstanceOf('InoOicServer\Oic\AuthSession\AuthSessionFactoryInterface', $this->service->getAuthSessionFactory());
    }

    public function testCreateSession()
    {
        $authStatus = $this->getMock('InoOicServer\Oic\User\Authentication\Status');
        $age = 120;
        $salt = 'secretsalt';
        $authSession = $this->createAuthSessionMock();
        
        $authSessionFactory = $this->createAuthSessionFactory();
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

    public function testSaveSessionWithExistingSession()
    {
        $userId = 'testuser';
        $method = 'dummy';
        $sessionId = '123qwe';
        
        $existingAuthSession = new AuthSession();
        $existingAuthSession->setId($sessionId);
        
        $authSession = new AuthSession();
        $authSession->setUserId($userId);
        $authSession->setMethod($method);
        
        $mapper = $this->createAuthSessionMapperMock();
        $mapper->expects($this->once())
            ->method('fetchByUserAndMethod')
            ->with($userId, $method)
            ->will($this->returnValue($existingAuthSession));
        $mapper->expects($this->once())
            ->method('delete')
            ->with($sessionId);
        $mapper->expects($this->once())
            ->method('save')
            ->with($authSession);
        
        $this->service->setAuthSessionMapper($mapper);
        $this->service->saveSession($authSession);
    }

    public function testSaveSession()
    {
        $userId = 'testuser';
        $method = 'dummy';
        
        $authSession = new AuthSession();
        $authSession->setUserId($userId);
        $authSession->setMethod($method);
        
        $mapper = $this->createAuthSessionMapperMock();
        $mapper->expects($this->once())
            ->method('fetchByUserAndMethod')
            ->with($userId, $method)
            ->will($this->returnValue(null));
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

    protected function createAuthSessionFactory()
    {
        $authSessionFactory = $this->getMock('InoOicServer\Oic\AuthSession\AuthSessionFactoryInterface');
        
        return $authSessionFactory;
    }
}