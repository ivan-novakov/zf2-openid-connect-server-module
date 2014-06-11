<?php

namespace InoOicServerTest\Oic\Authorize\Context;

use InoOicServer\Oic\Authorize\Context\SessionStorage;


class SessionStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SessionStorage
     */
    protected $storage;

    protected $sessionIndex = 'test_context';


    public function setUp()
    {
        $this->storage = new SessionStorage($this->createSessionContainerMock(), $this->sessionIndex);
    }


    public function testSave()
    {
        $context = $this->createContextMock();
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetSet')
            ->with($this->sessionIndex, $context);
        $this->storage->setSessionContainer($container);
        
        $this->storage->save($context);
    }


    public function testLoad()
    {
        $context = $this->createContextMock();
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetExists')
            ->with($this->sessionIndex)
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('offsetGet')
            ->with($this->sessionIndex)
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
            ->with($this->sessionIndex)
            ->will($this->returnValue(false));
        $this->storage->setSessionContainer($container);
        
        $this->assertNull($this->storage->load());
    }


    public function testClear()
    {
        $container = $this->createSessionContainerMock();
        $container->expects($this->once())
            ->method('offsetUnset')
            ->with($this->sessionIndex);
        $this->storage->setSessionContainer($container);
        
        $this->storage->clear();
    }
    
    /*
     *
    */
    protected function createContextMock()
    {
        $context = $this->getMock('InoOicServer\Oic\Authorize\Context\Context');
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