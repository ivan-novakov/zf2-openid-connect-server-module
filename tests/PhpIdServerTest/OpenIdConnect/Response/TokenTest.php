<?php

namespace PhpIdServerTest\OpenIdConnect\Response;

use PhpIdServer\OpenIdConnect\Entity;
use PhpIdServer\OpenIdConnect\Response;


class TokenTest extends \PHPUnit_Framework_TestCase
{

    protected $_response = NULL;


    public function setUp ()
    {
        $this->_response = new Response\Token(new \Zend\Http\Response());
    }


    public function testErrorResponse ()
    {
        $this->_response->setError('error_message', 'error description');
        $httpResponse = $this->_response->getHttpResponse();
        
        $this->assertInstanceOf('\Zend\Http\Response', $httpResponse);
        $this->assertEquals(400, $httpResponse->getStatusCode());
        $this->assertEquals('application/json', $httpResponse->getHeaders()
            ->get('Content-Type')
            ->getFieldValue());
        
        $data = \Zend\Json\Json::decode($httpResponse->getContent());
        $this->assertEquals('error_message', $data->error);
        $this->assertEquals('error description', $data->error_description);
    }


    public function testResponse ()
    {
        $entity = new Entity\Token(array(
            Entity\Token::FIELD_ACCESS_TOKEN => 'access_token_123', 
            Entity\Token::FIELD_REFRESH_TOKEN => 'refresh_token_123', 
            Entity\Token::FIELD_ID_TOKEN => 'id_token_123', 
            Entity\Token::FIELD_EXPIRES_IN => 200, 
            Entity\Token::FIELD_TOKEN_TYPE => 'bearer'
        ));
        
        $this->_response->setTokenEntity($entity);
        
        $httpResponse = $this->_response->getHttpResponse();
        $this->assertInstanceOf('\Zend\Http\Response', $httpResponse);
        $this->assertEquals(200, $httpResponse->getStatusCode());
        $this->assertEquals('application/json', $httpResponse->getHeaders()
            ->get('Content-Type')
            ->getFieldValue());
        
        $data = \Zend\Json\Json::decode($httpResponse->getContent(), \Zend\Json\Json::TYPE_ARRAY);
        $this->assertEquals('access_token_123', $data[Entity\Token::FIELD_ACCESS_TOKEN]);
        $this->assertEquals('refresh_token_123', $data[Entity\Token::FIELD_REFRESH_TOKEN]);
        $this->assertEquals('id_token_123', $data[Entity\Token::FIELD_ID_TOKEN]);
        $this->assertEquals(200, $data[Entity\Token::FIELD_EXPIRES_IN]);
        $this->assertEquals('bearer', $data[Entity\Token::FIELD_TOKEN_TYPE]);
    }
}