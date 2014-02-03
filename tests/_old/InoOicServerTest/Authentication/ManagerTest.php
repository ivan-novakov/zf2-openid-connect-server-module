<?php

namespace InoOicServerTest\Authentication;

use InoOicServer\Authentication\Manager;


class ManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $manager;

    protected $options = array(
        'foo' => 'bar'
    );


    public function setUp()
    {
        $this->manager = new Manager($this->options);
    }


    public function testConstructor()
    {
        $this->assertSame($this->options, (array) $this->manager->getOptions());
    }


    public function testSetOptions()
    {
        $options = array(
            'newfoo' => 'newbar'
        );
        $this->manager->setOptions($options);
        $this->assertSame($options, (array) $this->manager->getOptions());
    }


    public function testSetContext()
    {
        $context = $this->createContextMock();
        $this->manager->setContext($context);
        $this->assertSame($context, $this->manager->getContext());
    }


    public function testGetAuthenticationRouteName()
    {
        $options = array(
            Manager::OPT_BASE_ROUTE => 'auth-route-name'
        );
        $this->manager->setOptions($options);
        $this->assertSame('auth-route-name', $this->manager->getAuthenticationRouteName());
    }


    public function testGetAuthenticationHandlerWithMissingClientInContext()
    {
        $this->setExpectedException('RuntimeException', 'Client object not found in context');
        
        $context = $this->createContextMock();
        $this->manager->setContext($context);
        $this->manager->getAuthenticationHandler();
    }


    public function testGetAuthenticationHandlerWithNoHandlerAndNoDefaultHandler()
    {
        $this->setExpectedException('RuntimeException', 'No default authentication handler specified');
        
        $client = $this->createClientMock();
        $context = $this->createContextMock($client);
        $this->manager->setContext($context);
        $this->manager->getAuthenticationHandler();
    }


    public function testGetAuthenticationHandlerWithDefaultHandler()
    {
        $handlerName = 'foo-handler';
        
        $client = $this->createClientMock();
        $context = $this->createContextMock($client);
        $this->manager->setContext($context);
        $this->manager->setOptions(
            array(
                Manager::OPT_DEFAULT_AUTHENTICATION_HANDLER => $handlerName
            ));
        
        $this->assertSame($handlerName, $this->manager->getAuthenticationHandler());
    }


    public function testGetAuthenticationHandlerWithClientHandler()
    {
        $handlerName = 'foo-handler';
        
        $client = $this->createClientMock($handlerName);
        $context = $this->createContextMock($client);
        $this->manager->setContext($context);
        
        $this->assertSame($handlerName, $this->manager->getAuthenticationHandler());
    }
    
    /*
     * --------------------
     */
    protected function createContextMock($client = null)
    {
        $context = $this->getMock('InoOicServer\Context\AuthorizeContext');
        if ($client) {
            $context->expects($this->once())
                ->method('getClient')
                ->will($this->returnValue($client));
        }
        return $context;
    }


    protected function createClientMock($handler = null)
    {
        $client = $this->getMockBuilder('InoOicServer\Client\Client')
            ->setMethods(array(
            'getUserAuthenticationHandler'
        ))
            ->getMock();
        if ($handler) {
            $client->expects($this->once())
                ->method('getUserAuthenticationHandler')
                ->will($this->returnValue($handler));
        }
        return $client;
    }
}