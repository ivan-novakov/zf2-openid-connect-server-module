<?php

namespace PhpIdServerTest\Session;

use PhpIdServer\Session\SessionHydrator;
use PhpIdServer\Session\Session;


class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testHydrate ()
    {
        $data = $this->_getSessionData();
        $session = $this->_createSession($data);

        $this->assertEquals($data, $session->getArrayCopy());
    }


    protected function _createSession ($data)
    {
        $session = new Session();
        $session->populate($data);
        
        return $session;
    }


    protected function _getSessionData ()
    {
        return array(
            Session::FIELD_ID => 'session_id_123', 
            Session::FIELD_USER_ID => 'testuser', 
            Session::FIELD_AUTHENTICATION_TIME => '2012-08-01 00:00:00', 
            Session::FIELD_AUTHENTICATION_METHOD => 'dummy', 
            Session::FIELD_CREATE_TIME => '2012-09-08 00:00:00', 
            Session::FIELD_MODIFY_TIME => '2012-09-09 00:00:00', 
            Session::FIELD_EXPIRATION_TIME => '2012-09-13 00:00:00', 
            Session::FIELD_USER_DATA => 'serialized_user_data_123'
        );
    }
}