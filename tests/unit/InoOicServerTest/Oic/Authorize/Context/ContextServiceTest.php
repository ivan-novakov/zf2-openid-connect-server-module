<?php

namespace InoOicServerTest\Oic\Authorize\Context;

use InoOicServer\Oic\Authorize\Context\ContextService;


class ContextServiceTest extends \PHPUnit_Framework_Testcase
{


    public function testConstructor()
    {
        $storage = $this->createStorageMock();
        $factory = $this->createFactoryMock();
        
        $service = new ContextService($storage, $factory);
        
        $this->assertSame($storage, $service->getStorage(), 'Storage object is not the same');
        $this->assertSame($factory, $service->getFactory(), 'Factory object is not the same');
    }


    public function testConstructorWithImplicitFactory()
    {
        $storage = $this->createStorageMock();
        
        $service = new ContextService($storage);
        
        $this->assertSame($storage, $service->getStorage(), 'Storage object is not the same');
        $this->assertInstanceOf('InoOicServer\Oic\Authorize\Context\ContextFactory', $service->getFactory());
    }


    public function testCreateContext()
    {
        $context = $this->createContextMock();
        
        $factory = $this->createFactoryMock();
        $factory->expects($this->once())
            ->method('createContext')
            ->will($this->returnValue($context));
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('save')
            ->with($context);
        
        $service = new ContextService($storage, $factory);
        
        $this->assertSame($context, $service->createContext());
    }


    public function testSaveContext()
    {
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('save')
            ->with($context);
        
        $service = new ContextService($storage);
        $service->saveContext($context);
    }


    public function testLoadContext()
    {
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('load')
            ->will($this->returnValue($context));
        
        $service = new ContextService($storage);
        
        $this->assertSame($context, $service->loadContext());
    }


    public function testExistsValidContext()
    {
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('load')
            ->will($this->returnValue($context));
        
        $service = new ContextService($storage);
        
        $this->assertTrue($service->existsValidContext());
    }


    public function testExistsValidContextNegative()
    {
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('load')
            ->will($this->returnValue(null));
        
        $service = new ContextService($storage);
        
        $this->assertFalse($service->existsValidContext());
    }


    public function testClearContext()
    {
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('clear');
        
        $service = new ContextService($storage);
        $service->clearContext();
    }
    
    /*
     * 
     */
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStorageMock()
    {
        $storage = $this->getMock('InoOicServer\Oic\Authorize\Context\StorageInterface');
        
        return $storage;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createFactoryMock()
    {
        $factory = $this->getMock('InoOicServer\Oic\Authorize\Context\ContextFactoryInterface');
        
        return $factory;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createContextMock()
    {
        $context = $this->getMock('InoOicServer\Oic\Authorize\Context\Context');
        
        return $context;
    }
}