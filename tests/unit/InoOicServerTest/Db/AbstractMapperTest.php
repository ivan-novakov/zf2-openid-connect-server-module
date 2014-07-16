<?php

namespace InoOicServerTest\Db;

use InoOicServer\Db\AbstractMapper;


class AbstractMapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AbstractMapper
     */
    protected $mapper;


    public function setUp()
    {
        $this->mapper = $this->getMockBuilder('InoOicServer\Db\AbstractMapper')
            ->setConstructorArgs(array(
            $this->createDbAdapterMock(),
            $this->createFactoryMock(),
            $this->createHydratorMock()
        ))
            ->getMockForAbstractClass();
    }


    public function testSetDbAdapter()
    {
        $dbAdapter = $this->createDbAdapterMock();
        $this->mapper->setDbAdapter($dbAdapter);
        $this->assertSame($dbAdapter, $this->mapper->getDbAdapter());
    }


    public function testSetFactory()
    {
        $factory = $this->createFactoryMock();
        $this->mapper->setFactory($factory);
        $this->assertSame($factory, $this->mapper->getFactory());
    }


    public function testSetHydrator()
    {
        $hydrator = $this->createHydratorMock();
        $this->mapper->setHydrator($hydrator);
        $this->assertSame($hydrator, $this->mapper->getHydrator());
    }


    public function testGetImplicitSql()
    {
        $driver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $dbAdapter = new \Zend\Db\Adapter\Adapter($driver);
        $this->mapper->setDbAdapter($dbAdapter);
        
        $sql = $this->mapper->getSql();
        $this->assertInstanceOf('Zend\Db\Sql\Sql', $sql);
        $this->assertSame($dbAdapter, $sql->getAdapter());
        $this->assertSame($driver, $sql->getAdapter()
            ->getDriver());
    }


    public function testExecuteSingleEntityQueryWithTooManyResults()
    {
        $this->setExpectedException('InoOicServer\Db\Exception\InvalidResultException', 'Expected only one record');
        
        $select = $this->getMock('Zend\Db\Sql\Select');
        $params = array(
            'foo' => 'bar'
        );
        
        $results = $this->getMock('Zend\Db\Adapter\Driver\ResultInterface');
        $results->expects($this->any())
            ->method('count')
            ->will($this->returnValue(2));
        
        $mapper = $this->getMockBuilder('InoOicServer\Db\AbstractMapper')
            ->setConstructorArgs(array(
            $this->createDbAdapterMock(),
            $this->createFactoryMock(),
            $this->createHydratorMock()
        ))
            ->setMethods(array(
            'executeSelect'
        ))
            ->getMockForAbstractClass();
        
        $mapper->expects($this->once())
            ->method('executeSelect')
            ->with($select, $params)
            ->will($this->returnValue($results));
        
        $mapper->executeSingleEntityQuery($select, $params);
    }
    
    /*
     * 
     */
    protected function createDbAdapterMock()
    {
        $adapter = $this->getMock('Zend\Db\Adapter\AdapterInterface');
        
        return $adapter;
    }


    protected function createFactoryMock()
    {
        $factory = $this->getMock('InoOicServer\Oic\EntityFactoryInterface');
        
        return $factory;
    }


    protected function createHydratorMock()
    {
        $hydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
        
        return $hydrator;
    }
}