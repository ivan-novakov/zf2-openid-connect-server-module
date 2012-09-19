<?php

namespace PhpIdServerTest\OpenIdConnect\Entity;

use PhpIdServer\OpenIdConnect\Entity\Token;


class TokenTest extends \PHPUnit_Framework_TestCase
{

    protected $_token = NULL;


    public function setUp ()
    {
        $this->_token = new Token(array(
            Token::FIELD_ACCESS_TOKEN => 'access_token_123', 
            Token::FIELD_REFRESH_TOKEN => 'refresh_token_123', 
            Token::FIELD_ID_TOKEN => 'id_token_123', 
            Token::FIELD_EXPIRES_IN => 120, 
            Token::FIELD_TOKEN_TYPE => 'unknown'
        ));
    }


    public function testGetAccessToken ()
    {
        $this->assertEquals('access_token_123', $this->_token->getAccessToken());
    }


    public function testGetRefreshToken ()
    {
        $this->assertEquals('refresh_token_123', $this->_token->getRefreshToken());
    }


    public function testGetIdToken ()
    {
        $this->assertEquals('id_token_123', $this->_token->getIdToken());
    }


    public function testGetExpiresIn ()
    {
        $this->assertEquals(120, $this->_token->getExpiresIn());
    }


    public function testGetTokenType ()
    {
        $this->assertEquals('unknown', $this->_token->getTokenType());
    }
}