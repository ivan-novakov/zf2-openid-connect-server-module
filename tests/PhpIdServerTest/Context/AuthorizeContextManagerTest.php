<?php

namespace PhpIdServerTest\Context;

use PhpIdServer\Context\AuthorizeContextManager;


class AuthorizeContextManagerTest extends \PHPUnit_Framework_TestCase
{


    public function testLoadContext()
    {
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('load')
            ->will($this->returnValue($context));
        
        $requestFactory = $this->createAuthorizeRequestFactory();
        
        $manager = new AuthorizeContextManager($storage, $requestFactory);
        $this->assertSame($context, $manager->loadContext());
    }


    public function testPersistContext()
    {
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('save')
            ->with($context);
        
        $requestFactory = $this->createAuthorizeRequestFactory();
        
        $manager = new AuthorizeContextManager($storage, $requestFactory);
        $manager->persistContext($context);
    }


    public function testUnpersistContext()
    {
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('clear');
        
        $requestFactory = $this->createAuthorizeRequestFactory();
        
        $manager = new AuthorizeContextManager($storage, $requestFactory);
        $manager->unpersistContext();
    }


    public function testInitContextWithInitialRequest()
    {
        $request = $this->getMockBuilder('PhpIdServer\OpenIdConnect\Request\Authorize\Simple')
            ->disableOriginalConstructor()
            ->getMock();
        $httpRequest = $this->createHttpRequest();
        
        $requestFactory = $this->createAuthorizeRequestFactory();
        $requestFactory->expects($this->once())
            ->method('createRequest')
            ->with($httpRequest)
            ->will($this->returnValue($request));
        
        $context = $this->createContextMock();
        $context->expects($this->once())
            ->method('setRequest')
            ->with($request);
        
        $contextFactory = $this->getMock('PhpIdServer\Context\AuthorizeContextFactory');
        $contextFactory->expects($this->once())
            ->method('createContext')
            ->will($this->returnValue($context));
        
        $storage = $this->createStorageMock();
        
        $manager = $this->getMockBuilder('PhpIdServer\Context\AuthorizeContextManager')
            ->setConstructorArgs(array(
            $storage,
            $requestFactory,
            $contextFactory,
            $httpRequest
        ))
            ->setMethods(array(
            'isInitialHttpRequest'
        ))
            ->getMock();
        $manager->expects($this->once())
            ->method('isInitialHttpRequest')
            ->with($httpRequest)
            ->will($this->returnValue(true));
        
        $context = $manager->initContext();
    }


    public function testInitExistingContext()
    {
        $httpRequest = $this->createHttpRequest();
        
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        
        $requestFactory = $this->createAuthorizeRequestFactory();
        
        $manager = $this->getMockBuilder('PhpIdServer\Context\AuthorizeContextManager')
            ->setConstructorArgs(array(
            $storage,
            $requestFactory,
            null,
            $httpRequest
        ))
            ->setMethods(array(
            'isInitialHttpRequest',
            'loadContext'
        ))
            ->getMock();
        
        $manager->expects($this->once())
            ->method('isInitialHttpRequest')
            ->with($httpRequest)
            ->will($this->returnValue(false));
        
        $manager->expects($this->once())
            ->method('loadContext')
            ->will($this->returnValue($context));
        
        $this->assertSame($context, $manager->initContext());
    }


    public function testInitContextWithMissingContext()
    {
        $this->setExpectedException('PhpIdServer\Context\Exception\MissingContextException');
        
        $httpRequest = $this->createHttpRequest();
        
        $context = $this->createContextMock();
        
        $storage = $this->createStorageMock();
        
        $requestFactory = $this->createAuthorizeRequestFactory();
        
        $manager = $this->getMockBuilder('PhpIdServer\Context\AuthorizeContextManager')
            ->setConstructorArgs(array(
            $storage,
            $requestFactory,
            null,
            $httpRequest
        ))
            ->setMethods(array(
            'isInitialHttpRequest',
            'loadContext'
        ))
            ->getMock();
        
        $manager->expects($this->once())
            ->method('isInitialHttpRequest')
            ->with($httpRequest)
            ->will($this->returnValue(false));
        
        $manager->expects($this->once())
            ->method('loadContext')
            ->will($this->returnValue(null));
        
        $this->assertSame($context, $manager->initContext());
    }
    
    /*
     * -----------------------------------------------------------------------------
     */
    protected function createContextMock()
    {
        $context = $this->getMockBuilder('PhpIdServer\Context\AuthorizeContext')
            ->disableOriginalConstructor()
            ->getMock();
        
        return $context;
    }


    protected function createStorageMock()
    {
        $storage = $this->getMock('PhpIdServer\Context\Storage\StorageInterface');
        return $storage;
    }


    protected function createAuthorizeRequestFactory()
    {
        $factory = $this->getMock('PhpIdServer\OpenIdConnect\Request\Authorize\RequestFactory');
        return $factory;
    }


    protected function createHttpRequest()
    {
        return $this->getMock('Zend\Http\Request');
    }
}