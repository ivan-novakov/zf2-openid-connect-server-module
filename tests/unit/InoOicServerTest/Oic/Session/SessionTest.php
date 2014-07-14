<?php

namespace InoOicServerTest\Oic\Session;

use DateTime;
use InoOicServer\Oic\Session\Session;


class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testGettersAndSetters()
    {
        $id = '123';
        $authSessionId = 'qwerty';
        $createTime = '2014-01-02';
        $modifyTime = '2014-01-03';
        $expirationTime = '2014-01-31';
        $nonce = 'testnonce';
        
        $session = new Session();
        $session->setId($id);
        $session->setAuthSessionId($authSessionId);
        $session->setCreateTime($createTime);
        $session->setModifyTime($modifyTime);
        $session->setExpirationTime($expirationTime);
        $session->setNonce($nonce);
        
        $this->assertSame($id, $session->getId());
        $this->assertSame($authSessionId, $session->getAuthSessionId());
        $this->assertEquals(new DateTime($createTime), $session->getCreateTime());
        $this->assertEquals(new DateTime($modifyTime), $session->getModifyTime());
        $this->assertEquals(new DateTime($expirationTime), $session->getExpirationTime());
        $this->assertSame($nonce, $session->getNonce());
    }
}