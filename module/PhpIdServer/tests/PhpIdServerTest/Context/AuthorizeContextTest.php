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
    protected $_context = NULL;


    public function setUp ()
    {
        $this->_context = new Context\AuthorizeContext();
    }


    public function testIsUserAuthenticatedWithNoInfo ()
    {
        $this->assertFalse($this->_context->isUserAuthenticated());
    }


    public function testIsUserAuthenticatedFalse ()
    {
        $info = $this->getMock('PhpIdServer\Authentication\Info');
        $info->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(false));
        
        $this->_context->setAuthenticationInfo($info);
        
        $this->assertFalse($this->_context->isUserAuthenticated());
    }


    public function testIsUserAuthenticatedTrue ()
    {
        $info = $this->getMock('PhpIdServer\Authentication\Info');
        $info->expects($this->once())
            ->method('isAuthenticated')
            ->will($this->returnValue(true));
        
        $this->_context->setAuthenticationInfo($info);
        
        $this->assertTrue($this->_context->isUserAuthenticated());
    }
}