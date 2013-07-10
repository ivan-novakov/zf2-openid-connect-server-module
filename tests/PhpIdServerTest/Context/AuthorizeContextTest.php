<?php

namespace PhpIdServerTest\Context;

use PhpIdServer\Context;


class AuthorizeContextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Context object.
     * 
     * @var Context\AuthorizeContext
     */
    protected $context = NULL;


    public function setUp()
    {
        $this->context = new Context\AuthorizeContext();
    }


    public function testInitialStatus()
    {
        $this->assertSame(Context\AuthorizeContext::STATUS_UNKNOWN, $this->context->getStatus());
    }


    public function testSetStatus()
    {
        $status = 101;
        $this->context->setStatus($status);
        $this->assertSame($status, $this->context->getStatus()); 
    }


    public function testIsUserAuthenticatedWithNoInfo()
    {
        $this->assertFalse($this->context->isUserAuthenticated());
    }


    public function testIsUserAuthenticatedFalse()
    {
        $info = $this->getMock('PhpIdServer\Authentication\Info');
        $info->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(false));
        
        $this->context->setAuthenticationInfo($info);
        
        $this->assertFalse($this->context->isUserAuthenticated());
    }


    public function testIsUserAuthenticatedTrue()
    {
        $info = $this->getMock('PhpIdServer\Authentication\Info');
        $info->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(true));
        
        $this->context->setAuthenticationInfo($info);
        
        $this->assertTrue($this->context->isUserAuthenticated());
    }
}