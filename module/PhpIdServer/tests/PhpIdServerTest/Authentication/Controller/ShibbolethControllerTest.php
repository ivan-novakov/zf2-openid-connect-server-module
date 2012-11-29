<?php

namespace PhpIdServerTest\Authentication\Controller;

use PhpIdServer\User\User;
use PhpIdServer\Authentication\Controller\ShibbolethController;


class ShibbolethControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ShibbolethController
     */
    protected $_controller = null;


    public function setUp ()
    {
        $this->_controller = new ShibbolethController();
    }


    public function testGetServerVars ()
    {
        $serverVars = array(
            'foo' => 'bar'
        );
        
        $this->_controller->setServerVars($serverVars);
        $this->assertSame($serverVars, $this->_controller->getServerVars());
    }


    public function testGetSystemVars ()
    {
        $serverVars = array(
            'foo' => 'bar', 
            'origVar' => 'blah'
        );
        $this->_controller->setServerVars($serverVars);
        
        $systemVarsMap = array(
            'origVar' => 'systemVar'
        );
        $this->_controller->setOptions(array(
            ShibbolethController::OPT_SYSTEM_ATTRIBUTES_MAP => $systemVarsMap
        ));
        
        $expectedSystemVars = array(
            'systemVar' => 'blah'
        );
        
        $this->assertSame($expectedSystemVars, $this->_controller->getSystemVars());
    }


    public function testGetAttributes ()
    {
        $serverVars = array(
            'foo' => 'bar', 
            'origVar' => 'blah'
        );
        $this->_controller->setServerVars($serverVars);
        
        $attributeMap = array(
            'foo' => 'voo'
        );
        $this->_controller->setOptions(array(
            ShibbolethController::OPT_USER_ATTRIBUTES_MAP => $attributeMap
        ));
        
        $expectedAttributes = array(
            'voo' => 'bar'
        );
        $this->assertSame($expectedAttributes, $this->_controller->getAttributes());
    }


    public function testGetSystemVar ()
    {
        $systemVars = array(
            'foo' => 'bar'
        );
        
        $controller = $this->getMocK('PhpIdServer\Authentication\Controller\ShibbolethController', array(
            'getSystemVars'
        ));
        $controller->expects($this->exactly(2))
            ->method('getSystemVars')
            ->will($this->returnValue($systemVars));
        
        $this->assertSame('bar', $controller->getSystemVar('foo'));
        $this->assertNull($controller->getSystemVar('non existent'));
    }


    public function testGetAttribute ()
    {
        $attributes = array(
            'foo' => 'bar'
        );
        $controller = $this->getMocK('PhpIdServer\Authentication\Controller\ShibbolethController', array(
            'getAttributes'
        ));
        $controller->expects($this->exactly(2))
            ->method('getAttributes')
            ->will($this->returnValue($attributes));
        
        $this->assertSame('bar', $controller->getAttribute('foo'));
        $this->assertNull($controller->getAttribute('non existent'));
    }


    public function testExistsSession ()
    {
        $controller = $this->getMock('PhpIdServer\Authentication\Controller\ShibbolethController', array(
            'getSessionId'
        ));
        
        $controller->expects($this->at(0))
            ->method('getSessionId')
            ->will($this->returnValue('123'));
        
        $controller->expects($this->at(1))
            ->method('getSessionId')
            ->will($this->returnValue(null));
        
        $this->assertTrue($controller->existsSession(), 'existsSession() returns false, when there is a session ID');
        $this->assertFalse($controller->existsSession(), 'existsSession() returns true, when there is no session ID');
    }


    public function testAuthenticateWithoutSession ()
    {
        $this->setExpectedException('PhpIdServer\Authentication\Controller\Exception\AuthenticationException');
        
        $controller = $this->_getControllerMock(array(
            'existsSession'
        ));
        $controller->expects($this->once())
            ->method('existsSession')
            ->will($this->returnValue(false));
        
        $controller->authenticate();
    }


    public function testAuthenticateWithNoUserId ()
    {
        $this->setExpectedException('PhpIdServer\Authentication\Controller\Exception\AuthenticationException');
        
        $controller = $this->_getControllerMock(array(
            'existsSession', 
            'getAttributes'
        ));
        $controller->expects($this->once())
            ->method('existsSession')
            ->will($this->returnValue(true));
        $controller->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue(array()));
        
        $controller->authenticate();
    }


    public function testAuthenticateWithValidData ()
    {
        $userData = array(
            User::FIELD_ID => 'testuser'
        );
        
        $controller = $this->_getControllerMock(array(
            'existsSession', 
            'getAttributes'
        ));
        $controller->expects($this->once())
            ->method('existsSession')
            ->will($this->returnValue(true));
        $controller->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue($userData));
        
        $user = $controller->authenticate();
        $this->assertInstanceOf('PhpIdServer\User\User', $user);
        $this->assertSame('testuser', $user->getId());
    }


    /**
     * @param array $mockedMethods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getControllerMock (array $mockedMethods)
    {
        return $this->getMock('PhpIdServer\Authentication\Controller\ShibbolethController', $mockedMethods);
    }
}