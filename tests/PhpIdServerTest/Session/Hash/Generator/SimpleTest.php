<?php

namespace PhpIdServerTest\Session\Hash\Generator;

use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\Session\Session;
use PhpIdServer\Client\Client;
use PhpIdServer\Session\Hash\Generator;


class SimpleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The generator object.
     * 
     * @var Generator\Simple
     */
    protected $_generator = NULL;

    /**
     * Client object.
     * 
     * @var Client
     */
    protected $_client = NULL;

    /**
     * Session object.
     * 
     * @var Session
     */
    protected $_session = NULL;


    public function setUp ()
    {
        $this->_generator = new Generator\Simple();
        
        $this->_client = new Client(array(
            Client::FIELD_ID => 'testclient'
        ));
        
        $this->_session = new Session(array(
            Session::FIELD_ID => 'session_id_123'
        ));
    }


    public function testGenerateAuthorizationCode ()
    {
        $code = $this->_generator->generateAuthorizationCode($this->_session, $this->_client);
        
        $this->assertInternalType('string', $code);
        $this->assertEquals(40, strlen($code));
    }


    public function testGenerateAccessToken ()
    {
        $code = $this->_generator->generateAccessToken($this->_session, $this->_client);
        
        $this->assertInternalType('string', $code);
        $this->assertEquals(40, strlen($code));
    }


    public function testGenerateRefreshToken ()
    {
        $accessToken = new AccessToken(array(
            'token' => '1234567890123456789012345678901234567890'
        ));
        
        $code = $this->_generator->generateRefreshToken($accessToken, $this->_client);
        
        $this->assertInternalType('string', $code);
        $this->assertEquals(40, strlen($code));
    }
}