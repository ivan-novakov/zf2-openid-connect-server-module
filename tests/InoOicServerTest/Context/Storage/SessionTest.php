<?php

namespace InoOicServerTest\Context\Storage;

use InoOicServer\Context;


class SessionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Context\Storage\Session
     */
    protected $storage;

    protected $sessionKey = 'test_context';


    public function setUp()
    {
        $this->storage = new Context\Storage\Session();
        $this->storage->setSessionKey($this->sessionKey);
    }


    public function tearDown()
    {
        $this->storage->clear();
    }


    public function testSave()
    {
        $context = $this->createContextMock();
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetSet')
            ->with($this->sessionKey, $context);
        $this->storage->setSessionContainer($container);
        
        $this->storage->save($context);
    }


    public function testLoad()
    {
        $context = $this->createContextMock();
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetExists')
            ->with($this->sessionKey)
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('offsetGet')
            ->with($this->sessionKey)
            ->will($this->returnValue($context));
        $this->storage->setSessionContainer($container);
        
        $this->assertSame($context, $this->storage->load());
    }


    public function testLoadWithNonExistingContext()
    {
        $context = $this->createContextMock();
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetExists')
            ->with($this->sessionKey)
            ->will($this->returnValue(false));
        $this->storage->setSessionContainer($container);
        
        $this->assertNull($this->storage->load());
    }


    public function testClear()
    {
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetUnset')
            ->with($this->sessionKey);
        $this->storage->setSessionContainer($container);
        
        $this->storage->clear();
    }
    
    /*
     * 
     */
    protected function createContextMock()
    {
        $context = $this->getMock('InoOicServer\Context\ContextInterface');
        return $context;
    }


    protected function createSessionContainerMock()
    {
        $container = $this->getMockBuilder('Zend\Session\Container')
            ->disableOriginalConstructor()
            ->getMock();
        return $container;
    }
}