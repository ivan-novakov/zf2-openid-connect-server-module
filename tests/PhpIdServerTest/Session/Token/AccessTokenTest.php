<?php

namespace PhpIdServerTest\Session\Token;

use PhpIdServer\Session\Token\AccessToken;


class AccessTokenTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The access token.
     * 
     * @var AccessToken
     */
    protected $_token = NULL;


    public function setUp ()
    {
        $this->_token = new AccessToken();
        $this->_token->populate($this->_getData());
    }


    public function testToArray ()
    {
        $this->assertEquals($this->_getData(), $this->_token->toArray());
    }


    public function testGetToken ()
    {
        $this->assertEquals('access_token_123', $this->_token->getToken());
    }


    public function testGetSessionId ()
    {
        $this->assertEquals('session_id_123', $this->_token->getSessionId());
    }


    public function getClientId ()
    {
        $this->assertEquals('testclient', $this->_token->getClientId());
    }


    public function testGetIssueTime ()
    {
        $this->assertInstanceOf('\DateTime', $this->_token->getIssueTime());
    }


    public function testGetExpirationTime ()
    {
        $this->assertInstanceOf('\DateTime', $this->_token->getExpirationTime());
    }


    public function testGetScope ()
    {
        $this->assertEquals('openid', $this->_token->getScope());
    }


    public function testGetType ()
    {
        $this->assertEquals('Bearer', $this->_token->getType());
    }


    public function testIsExpired ()
    {
        $this->_token->setExpirationTime(new \DateTime('yesterday'));
        $this->assertTrue($this->_token->isExpired());
    }


    public function testIsNotExpired ()
    {
        $this->_token->setExpirationTime(new \DateTime('tomorrow'));
        $this->assertFalse($this->_token->isExpired());
    }


    public function testExpiresIn ()
    {
        $this->_token->populate(array(
            AccessToken::FIELD_EXPIRATION_TIME => new \DateTime('tomorrow')
        ));
        
        $seconds = $this->_token->expiresIn();
        $this->assertInternalType('integer', $seconds);
        $this->assertGreaterThan(0, $seconds);
    }


    protected function _getData ()
    {
        return array(
            AccessToken::FIELD_TOKEN => 'access_token_123', 
            AccessToken::FIELD_SESSION_ID => 'session_id_123', 
            AccessToken::FIELD_CLIENT_ID => 'testclient', 
            AccessToken::FIELD_ISSUE_TIME => '2012-09-01 00:00:00', 
            AccessToken::FIELD_EXPIRATION_TIME => '2013-09-01 00:00:00', 
            AccessToken::FIELD_SCOPE => 'openid', 
            AccessToken::FIELD_TYPE => 'Bearer'
        );
    }
}