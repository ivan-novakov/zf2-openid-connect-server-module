<?php

namespace InoOicServerTest\Oic\AuthSession;

use InoOicServer\Oic\AuthSession\AuthSession;


class AuthSessionTest extends \PHPUnit_Framework_TestCase
{


    public function testSettersAndGetters()
    {
        $id = 'foo';
        $method = 'dummy';
        $createTime = '2014-06-05 10:00:00';
        $expirationTime = '2014-06-05 12:00:00';
        $userId = 'testuser';
        $userData = 'user_data';
        $user = $this->getMock('InoOicServer\Oic\User\User');
        
        $authSession = new AuthSession();
        $authSession->setId($id);
        $authSession->setMethod($method);
        $authSession->setCreateTime($createTime);
        $authSession->setExpirationTime($expirationTime);
        $authSession->setUserId($userId);
        $authSession->setUserData($userData);
        $authSession->setUser($user);
        
        $this->assertSame($id, $authSession->getId());
        $this->assertSame($method, $authSession->getMethod());
        $this->assertEquals(new \DateTime($createTime), $authSession->getCreateTime());
        $this->assertEquals(new \DateTime($expirationTime), $authSession->getExpirationTime());
        $this->assertSame($userId, $authSession->getUserId());
        $this->assertSame($userData, $authSession->getUserData());
        $this->assertSame($user, $authSession->getUser());
    }
}