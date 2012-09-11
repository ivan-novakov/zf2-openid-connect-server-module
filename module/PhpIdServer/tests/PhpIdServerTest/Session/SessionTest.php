<?php

namespace PhpIdServerTest\Session;

use PhpIdServer\Session;


class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testCreateFactory ()
    {
        $this->markTestIncomplete();
        $userData = array(
            'id' => 'testuser'
        );
        
        $session = Session\Session::create('sessionid', 'testuser', 'testclient', '1234', 'dummy', $userData, 'accesstoken', 'refreshtoken');
        
        $this->assertEquals('sessionid', $session->getId());
        $this->assertEquals('testuser', $session->getUserId());
        $this->assertEquals('testclient', $session->getClientId());
        $this->assertEquals('1234', $session->getAuthenticationTime());
        $this->assertEquals('dummy', $session->getAuthenticationMethod());
        $this->assertEquals($userData, $session->getUserData());
    }


    public function testMissingUserId ()
    {
        $this->setExpectedException('\PhpIdServer\Session\Exception\MissingValueException');
        
        $session = new Session\Session();
    }


    public function testMissingClientId ()
    {
        $this->setExpectedException('\PhpIdServer\Session\Exception\MissingValueException');
        
        $session = new Session\Session(array(
            Session\Session::FIELD_USER_ID => 'testuser'
        ));
    }
}