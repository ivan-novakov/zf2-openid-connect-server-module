<?php

namespace InoOicServerTest\Oic\Session;

use DateTime;
use Zend\Stdlib\ArrayObject;
use InoOicServer\Oic\Session\Session;


class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testGettersAndSetters()
    {
        $id = '123';
        $userId = 'testuser';
        $authSessionId = 'qwerty';
        $createTime = '2014-01-02';
        $modifyTime = '2014-01-03';
        $expirationTime = '2014-01-31';
        $authTime = '2014-01-01';
        $authMethod = 'dummy';
        $userData = array(
            'foo' => 'bar'
        );
        
        $session = new Session();
        $session->setId($id);
        $session->setUserId($userId);
        $session->setAuthenticationSessionId($authSessionId);
        $session->setCreateTime($createTime);
        $session->setModifyTime($modifyTime);
        $session->setExpirationTime($expirationTime);
        $session->setAuthenticationTime($authTime);
        $session->setAuthenticationMethod($authMethod);
        $session->setUserData($userData);
        
        $this->assertSame($id, $session->getId());
        $this->assertSame($userId, $session->getUserId());
        $this->assertSame($authSessionId, $session->getAuthenticationSessionId());
        $this->assertSame($authMethod, $session->getAuthenticationMethod());
        $this->assertEquals(new DateTime($createTime), $session->getCreateTime());
        $this->assertEquals(new DateTime($modifyTime), $session->getModifyTime());
        $this->assertEquals(new DateTime($expirationTime), $session->getExpirationTime());
        $this->assertEquals(new DateTime($authTime), $session->getAuthenticationTime());
        $this->assertEquals(new ArrayObject($userData), $session->getUserData());
    }
}