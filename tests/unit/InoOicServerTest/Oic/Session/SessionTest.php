<?php

namespace InoOicServerTest\Oic\Session;

use DateTime;
use InoOicServer\Oic\Session\Session;


class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testGettersAndSetters()
    {
        $id = '123';
        $user = $this->getMock('InoOicServer\Oic\User\User');
        $authSessionId = 'qwerty';
        $createTime = '2014-01-02';
        $modifyTime = '2014-01-03';
        $expirationTime = '2014-01-31';
        $authTime = '2014-01-01';
        $authMethod = 'dummy';
        
        $session = new Session();
        $session->setId($id);
        $session->setUser($user);
        $session->setAuthenticationSessionId($authSessionId);
        $session->setCreateTime($createTime);
        $session->setModifyTime($modifyTime);
        $session->setExpirationTime($expirationTime);
        $session->setAuthenticationTime($authTime);
        $session->setAuthenticationMethod($authMethod);
        
        $this->assertSame($id, $session->getId());
        $this->assertSame($user, $session->getUser());
        $this->assertSame($authSessionId, $session->getAuthenticationSessionId());
        $this->assertSame($authMethod, $session->getAuthenticationMethod());
        $this->assertEquals(new DateTime($createTime), $session->getCreateTime());
        $this->assertEquals(new DateTime($modifyTime), $session->getModifyTime());
        $this->assertEquals(new DateTime($expirationTime), $session->getExpirationTime());
        $this->assertEquals(new DateTime($authTime), $session->getAuthenticationTime());
    }
}