<?php

namespace InoOicServerTest\Session;

use InoOicServer\Session\Token\AuthorizationCode;
use InoOicServer\Authentication\Info;
use InoOicServer\User\User;
use InoOicServer\Session\IdGenerator;
use InoOicServer\Session\Storage;
use InoOicServer\Session\SessionManager;
use InoOicServer\Session\Hash;
use InoOicServer\User\Serializer\Serializer;


class SessionManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The session manager object.
     * 
     * @var SessionManager
     */
    protected $manager = NULL;


    public function setUp()
    {
        $this->manager = new SessionManager();
        // FIXME - replace with mock
        $this->manager->setStorage(new Storage\Dummy());
    }


    public function testConstructor()
    {
        $options = array(
            'foo' => 'bar'
        );
        
        $manager = new SessionManager($options);
        $this->assertSame($options, $manager->getOptions()
            ->toArray());
    }


    public function testSetOptions()
    {
        $options = array(
            'foo' => 'bar'
        );
        
        $this->manager->setOptions($options);
        $this->assertSame($options, $this->manager->getOptions()
            ->toArray());
    }


    public function testCreateSession()
    {
        $user = $this->createUser();
        $info = $this->createAuthenticationInfo();
        
        $this->manager->setSessionIdGenerator($this->createSessionIdGeneratorStub());
        $this->manager->setUserSerializer($this->createUserSerializerStub());
        
        $session = $this->manager->createSession($user, $info);
        
        $this->assertInstanceOf('\InoOicServer\Session\Session', $session);
        $this->assertEquals(md5('test'), $session->getId());
        $this->assertEquals('serialized_user_data_123', $session->getUserData());
    }


    public function testCreateAuthorizationCode()
    {
        $session = $this->createSessionStub();
        $client = $this->createClientStub();
        
        $this->manager->setTokenGenerator($this->createTokenGeneratorStub());
        
        $authorizationCode = $this->manager->createAuthorizationCode($session, $client);
        
        $this->assertInstanceOf('\InoOicServer\Session\Token\AuthorizationCode', $authorizationCode);
        $this->assertEquals('generated_token', $authorizationCode->getCode());
    }


    public function testGetAuthorizationCode()
    {
        $session = $this->createSessionStub();
        $client = $this->createClientStub();
        
        $this->manager->setTokenGenerator($this->createTokenGeneratorStub());
        
        $authorizationCode = $this->manager->createAuthorizationCode($session, $client);
        
        $loadedCode = $this->manager->getAuthorizationCode($authorizationCode->getCode());
        
        $this->assertEquals($loadedCode->toArray(), $authorizationCode->toArray());
    }


    public function testCreateAccessToken()
    {
        $session = $this->createSessionStub();
        $client = $this->createClientStub();
        
        $this->manager->setTokenGenerator($this->createTokenGeneratorStub());
        
        $accessToken = $this->manager->createAccessToken($session, $client);
        
        $this->assertInstanceOf('\InoOicServer\Session\Token\AccessToken', $accessToken);
        $this->assertEquals('generated_access_token', $accessToken->getToken());
        $this->assertEquals(md5('test'), $accessToken->getSessionId());
    }


    public function testGetAccessToken()
    {
        $session = $this->createSessionStub();
        $client = $this->createClientStub();
        
        $this->manager->setTokenGenerator($this->createTokenGeneratorStub());
        
        $accessToken = $this->manager->createAccessToken($session, $client);
        
        $loadedAccessToken = $this->manager->getAccessToken($accessToken->getToken());
        
        $this->assertEquals($loadedAccessToken->toArray(), $accessToken->toArray());
    }
    
    /*
     * Helper methods
     */
    protected function createSessionStub()
    {
        $session = $this->getMock('\InoOicServer\Session\Session');
        $session->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(md5('test')));
        
        return $session;
    }


    protected function createClientStub()
    {
        $client = $this->getMock('\InoOicServer\Client\Client');
        $client->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('testclient'));
        
        return $client;
    }


    protected function createSessionIdGeneratorStub()
    {
        $idGenerator = $this->getMock('\InoOicServer\Session\IdGenerator\Simple');
        $idGenerator->expects($this->any())
            ->method('generateId')
            ->will($this->returnValue(md5('test')));
        
        return $idGenerator;
    }


    protected function createUserSerializerStub()
    {
        $serializer = $this->getMock('\InoOicServer\User\Serializer\Serializer');
        $serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue('serialized_user_data_123'));
        
        $serializer->expects($this->any())
            ->method('unserialize')
            ->will($this->returnValue($this->createUser()));
        
        return $serializer;
    }


    protected function createTokenGeneratorStub()
    {
        $generator = $this->getMock('\InoOicServer\Session\Hash\Generator\Simple');
        $generator->expects($this->any())
            ->method('generateAuthorizationCode')
            ->will($this->returnValue('generated_token'));
        
        $generator->expects($this->any())
            ->method('generateAccessToken')
            ->will($this->returnValue('generated_access_token'));
        
        return $generator;
    }


    protected function createUser()
    {
        return new User(array(
            User::FIELD_ID => 'testuser'
        ));
    }


    protected function createAuthenticationInfo()
    {
        return new Info(
            array(
                Info::FIELD_METHOD => 'dummy',
                Info::FIELD_TIME => new \DateTime('now')
            ));
    }
}