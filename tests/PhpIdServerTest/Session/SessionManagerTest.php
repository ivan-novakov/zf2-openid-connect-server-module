<?php

namespace PhpIdServerTest\Session;

use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Authentication\Info;
use PhpIdServer\User\User;
use PhpIdServer\Session\IdGenerator;
use PhpIdServer\Session\Storage;
use PhpIdServer\Session\SessionManager;
use PhpIdServer\Session\Hash;
use PhpIdServer\User\Serializer\Serializer;


class SessionManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The session manager object.
     * 
     * @var SessionManager
     */
    protected $_manager = NULL;


    public function setUp ()
    {
        $this->_manager = new SessionManager();
        $this->_manager->setStorage(new Storage\Dummy());
    }


    public function testCreateSession ()
    {
        $user = $this->_getUser();
        $info = $this->_getAuthenticationInfo();
        
        $this->_manager->setSessionIdGenerator($this->_getSessionIdGeneratorStub());
        $this->_manager->setUserSerializer($this->_getUserSerializerStub());
        
        $session = $this->_manager->createSession($user, $info);
        
        $this->assertInstanceOf('\PhpIdServer\Session\Session', $session);
        $this->assertEquals(md5('test'), $session->getId());
        $this->assertEquals('serialized_user_data_123', $session->getUserData());
    }


    public function testCreateAuthorizationCode ()
    {
        $session = $this->_getSessionStub();
        $client = $this->_getClientStub();
        
        $this->_manager->setTokenGenerator($this->_getTokenGeneratorStub());
        
        $authorizationCode = $this->_manager->createAuthorizationCode($session, $client);
        
        $this->assertInstanceOf('\PhpIdServer\Session\Token\AuthorizationCode', $authorizationCode);
        $this->assertEquals('generated_token', $authorizationCode->getCode());
    }


    public function testGetAuthorizationCode ()
    {
        $session = $this->_getSessionStub();
        $client = $this->_getClientStub();
        
        $this->_manager->setTokenGenerator($this->_getTokenGeneratorStub());
        
        $authorizationCode = $this->_manager->createAuthorizationCode($session, $client);
        
        $loadedCode = $this->_manager->getAuthorizationCode($authorizationCode->getCode());
        
        $this->assertEquals($loadedCode->toArray(), $authorizationCode->toArray());
    }


    public function testCreateAccessToken ()
    {
        $session = $this->_getSessionStub();
        $client = $this->_getClientStub();
        
        $this->_manager->setTokenGenerator($this->_getTokenGeneratorStub());
        
        $accessToken = $this->_manager->createAccessToken($session, $client);
        
        $this->assertInstanceOf('\PhpIdServer\Session\Token\AccessToken', $accessToken);
        $this->assertEquals('generated_access_token', $accessToken->getToken());
        $this->assertEquals(md5('test'), $accessToken->getSessionId());
    }


    public function testGetAccessToken ()
    {
        $session = $this->_getSessionStub();
        $client = $this->_getClientStub();
        
        $this->_manager->setTokenGenerator($this->_getTokenGeneratorStub());
        
        $accessToken = $this->_manager->createAccessToken($session, $client);
        
        $loadedAccessToken = $this->_manager->getAccessToken($accessToken->getToken());
        
        $this->assertEquals($loadedAccessToken->toArray(), $accessToken->toArray());
    }
    
    /*
     * Helper methods
     */
    protected function _getSessionStub ()
    {
        $session = $this->getMock('\PhpIdServer\Session\Session');
        $session->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(md5('test')));
        
        return $session;
    }


    protected function _getClientStub ()
    {
        $client = $this->getMock('\PhpIdServer\Client\Client');
        $client->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('testclient'));
        
        return $client;
    }


    protected function _getSessionIdGeneratorStub ()
    {
        $idGenerator = $this->getMock('\PhpIdServer\Session\IdGenerator\Simple');
        $idGenerator->expects($this->any())
            ->method('generateId')
            ->will($this->returnValue(md5('test')));
        
        return $idGenerator;
    }


    protected function _getUserSerializerStub ()
    {
        $serializer = $this->getMock('\PhpIdServer\User\Serializer\Serializer');
        $serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue('serialized_user_data_123'));
        
        $serializer->expects($this->any())
            ->method('unserialize')
            ->will($this->returnValue($this->_getUser()));
        
        return $serializer;
    }


    protected function _getTokenGeneratorStub ()
    {
        $generator = $this->getMock('\PhpIdServer\Session\Hash\Generator\Simple');
        $generator->expects($this->any())
            ->method('generateAuthorizationCode')
            ->will($this->returnValue('generated_token'));
        
        $generator->expects($this->any())
            ->method('generateAccessToken')
            ->will($this->returnValue('generated_access_token'));
        
        return $generator;
    }


    protected function _getUser ()
    {
        return new User(array(
            User::FIELD_ID => 'testuser'
        ));
    }


    protected function _getAuthenticationInfo ()
    {
        return new Info(array(
            Info::FIELD_METHOD => 'dummy', 
            Info::FIELD_TIME => new \DateTime('now')
        ));
    }
}