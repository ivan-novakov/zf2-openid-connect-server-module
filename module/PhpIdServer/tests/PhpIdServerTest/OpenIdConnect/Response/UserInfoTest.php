<?php

namespace PhpIdServerTest\OpenIdConnect\Response;

use PhpIdServer\User\User;
use PhpIdServer\OpenIdConnect\Response;


class UserInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The user info response.
     * 
     * @var Response\UserInfo
     */
    protected $_response = NULL;


    public function setUp ()
    {
        $this->_response = new Response\UserInfo(new \Zend\Http\Response());
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


    public function testGetHttpResponse ()
    {
        $userData = array(
            User::FIELD_ID => 'testuser', 
            User::FIELD_NAME => 'Test User'
        );
        
        $this->_response->setUserData($userData);
        
        $httpResponse = $this->_response->getHttpResponse();
        
        $this->assertInstanceOf('\Zend\Http\Response', $httpResponse);
        $this->assertEquals(200, $httpResponse->getStatusCode());
        $this->assertEquals('application/json', $httpResponse->getHeaders()
            ->get('Content-Type')
            ->getFieldValue());
        
        $data = \Zend\Json\Json::decode($httpResponse->getContent(), \Zend\Json\Json::TYPE_ARRAY);
        
        $this->assertEquals('testuser', $data[User::FIELD_ID]);
        $this->assertEquals('Test User', $data[User::FIELD_NAME]);
    }
}