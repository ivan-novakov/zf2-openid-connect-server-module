<?php

namespace PhpIdServerTest\Session\Token;

use PhpIdServer\Session\Token\AuthorizationCode;


class AuthorizationCodeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Authorization code entity.
     * 
     * @var AuthorizationCode
     */
    protected $_authorizationCode = NULL;


    public function setUp ()
    {
        $this->_authorizationCode = new AuthorizationCode();
    }


    public function testHydrate ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->assertEquals($this->_getData(), $this->_authorizationCode->getArrayCopy());
    }


    public function testGetCode ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->assertEquals('authorization_code_123', $this->_authorizationCode->getCode());
    }


    public function testGetId ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->assertEquals('authorization_code_123', $this->_authorizationCode->getId());
    }


    public function testGetEntityName ()
    {
        $this->assertEquals('authorization_code', $this->_authorizationCode->getEntityName());
    }


    public function testGetExpirationTime ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->assertInstanceOf('\DateTime', $this->_authorizationCode->getExpirationTime());
    }


    public function testGetIssueTime ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->assertInstanceOf('\DateTime', $this->_authorizationCode->getIssueTime());
    }


    public function testIsExpired ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->_authorizationCode->setExpirationTime(new \DateTime('yesterday'));
        $this->assertTrue($this->_authorizationCode->isExpired());
    }


    public function testIsNotExpired ()
    {
        $this->_authorizationCode->populate($this->_getData());
        
        $this->_authorizationCode->setExpirationTime(new \DateTime('tomorrow'));
        $this->assertFalse($this->_authorizationCode->isExpired());
    }


    protected function _getData ()
    {
        return array(
            AuthorizationCode::FIELD_CODE => 'authorization_code_123', 
            AuthorizationCode::FIELD_SESSION_ID => 'session_id_123', 
            AuthorizationCode::FIELD_ISSUE_TIME => '2012-09-01 00:00:00', 
            AuthorizationCode::FIELD_EXPIRATION_TIME => '2012-10-01 00:00:00', 
            AuthorizationCode::FIELD_CLIENT_ID => 'testclient', 
            AuthorizationCode::FIELD_SCOPE => 'openid'
        );
    }
}