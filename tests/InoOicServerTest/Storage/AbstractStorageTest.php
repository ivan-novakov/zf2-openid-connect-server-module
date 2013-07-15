<?php

namespace InoOicServerTest\Storage;

use InoOicServer\Storage\AbstractStorage;


class AbstractStorageTest extends \PHPUnit_Framework_Testcase
{


    public function testConstructor()
    {
        $options = array(
            'foo' => 'bar'
        );
        $dbAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();
        
        $storage = $this->getMockBuilder('InoOicServer\Storage\AbstractStorage')
            ->setConstructorArgs(array(
            $dbAdapter,
            $options
        ))
            ->getMockForAbstractClass();
        
        $this->assertSame($options, $storage->getOptions()
            ->toArray());
        $this->assertSame($dbAdapter, $storage->getAdapter());
    }


    public function testSetOptions()
    {
        $storage = $this->getMockBuilder('InoOicServer\Storage\AbstractStorage')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        
        $options = array(
            'foo' => 'bar'
        );
        
        $storage->setOptions($options);
        $this->assertSame($options, $storage->getOptions()
            ->toArray());
    }


    public function testSetAdapter()
    {
        $storage = $this->getMockBuilder('InoOicServer\Storage\AbstractStorage')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        
        $dbAdapter = $this->createAdapterMock();
        $storage->setAdapter($dbAdapter);
        $this->assertSame($dbAdapter, $storage->getAdapter());
    }
    
    /*
     * ---------------------------------------
     */
    protected function createAdapterMock()
    {
        $dbAdapter = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();
        return $dbAdapter;
    }
}