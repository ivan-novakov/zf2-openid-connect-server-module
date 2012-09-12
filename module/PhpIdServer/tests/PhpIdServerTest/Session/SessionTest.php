<?php

namespace PhpIdServerTest\Session;

use PhpIdServer\Session\Session;


class SessionTest extends \PHPUnit_Framework_TestCase
{


    public function testCreateFactory ()
    {
        $session = $this->_createSession();
        
        $this->assertEquals('session_id_123', $session->getId());
        $this->assertEquals('testuser', $session->getUserId());
        $this->assertEquals('testclient', $session->getClientId());
        $this->assertEquals('2012-09-12 00:00:00', $session->getAuthenticationTime());
        $this->assertEquals('dummy', $session->getAuthenticationMethod());
        $this->assertEquals('serialized_user_data_123', $session->getUserData());
        $this->assertEquals('access_token_123', $session->getAccessToken());
        $this->assertEquals('refresh_token_123', $session->getRefreshToken());
        $this->assertEquals('2012-09-08 00:00:00', $session->getCtime());
        $this->assertEquals('2012-09-09 00:00:00', $session->getMtime());
    }


    protected function _createSession (Array $override = array())
    {
        $sessionId = isset($override['sessionId']) ? $override['sessionId'] : 'session_id_123';
        $userId = isset($override['userId']) ? $override['userId'] : 'testuser';
        $clientId = isset($override['clientId']) ? $override['clientId'] : 'testclient';
        $authenticationTime = '2012-09-12 00:00:00';
        $method = 'dummy';
        $code = 'authorization_code_123';
        $data = 'serialized_user_data_123';
        $accessToken = 'access_token_123';
        $refreshToken = 'refresh_token_123';
        $ctime = '2012-09-08 00:00:00';
        $mtime = '2012-09-09 00:00:00';
        
        return Session::create($sessionId, $userId, $clientId, $authenticationTime, $method, $code, $data, $accessToken, $refreshToken, $ctime, $mtime);
    }
}