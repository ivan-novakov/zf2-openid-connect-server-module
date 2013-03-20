<?php

namespace PhpIdServerTest\Client\Authentication\Method;

use PhpIdServer\Client\Authentication\Method\SharedSecret;


class SharedSecretTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SharedSecret
     */
    protected $method = null;


    public function setUp()
    {
        $this->method = new SharedSecret();
    }


    public function testAuthenticateNoLocalSecret()
    {
        $info = $this->_getInfoMock(null);
        $data = $this->_getDataMockO('foo');
        
        $result = $this->method->authenticate($info, $data);
        $this->assertFalse($result->isAuthenticated());
    }


    public function testAuthenticateNoRemoteSecret()
    {
        $info = $this->_getInfoMock('foo');
        $data = $this->_getDataMockO(null);
        
        $result = $this->method->authenticate($info, $data);
        $this->assertFalse($result->isAuthenticated());
    }


    public function testAuthenticateNoSecretMatch()
    {
        $info = $this->_getInfoMock('foo');
        $data = $this->_getDataMockO('bar');
        
        $result = $this->method->authenticate($info, $data);
        $this->assertFalse($result->isAuthenticated());
    }


    public function testAuthenticateOk()
    {
        $info = $this->_getInfoMock('foo');
        $data = $this->_getDataMockO('foo');
        
        $result = $this->method->authenticate($info, $data);
        $this->assertTrue($result->isAuthenticated());
    }


    protected function _getInfoMock($value)
    {
        $info = $this->getMockBuilder('PhpIdServer\Client\Authentication\Info')
            ->disableOriginalConstructor()
            ->getMock();
        $info->expects($this->once())
            ->method('getOption')
            ->with(SharedSecret::AUTH_INFO_SECRET)
            ->will($this->returnValue($value));
        
        return $info;
    }


    protected function _getDataMockO($value)
    {
        $data = $this->getMockBuilder('PhpIdServer\Client\Authentication\Data')
            ->disableOriginalConstructor()
            ->getMock();
        
        $data->expects($this->any())
            ->method('getParam')
            ->with(SharedSecret::AUTH_DATA_SECRET)
            ->will($this->returnValue($value));
        
        return $data;
    }
}