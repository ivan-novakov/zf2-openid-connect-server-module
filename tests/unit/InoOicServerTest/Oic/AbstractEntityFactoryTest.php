<?php

namespace InoOicServerTest\Oic;


class AbstractEntityFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \InoOicServer\Oic\AbstractEntityFactory
     */
    protected $factory;


    public function setUp()
    {
        $this->factory = $this->getMockForAbstractClass('InoOicServer\Oic\AbstractEntityFactory');
    }


    public function testGetImplicitHydrator()
    {
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\ClassMethods', $this->factory->getHydrator());
    }


    public function testSetHydrator()
    {
        $hydrator = $this->createHydratorMock();
        $this->factory->setHydrator($hydrator);
        
        $this->assertSame($hydrator, $this->factory->getHydrator());
    }


    public function testCreateEntityFromData()
    {
        $entityData = array(
            'foo' => 'bar'
        );
        $entity = $this->createEntityMock();
        $hydratedEntity = $this->createEntityMock();
        
        $hydrator = $this->createHydratorMock();
        $hydrator->expects($this->once())
            ->method('hydrate')
            ->with($entityData, $entity)
            ->will($this->returnValue($hydratedEntity));
        
        $factory = $this->getMockBuilder('InoOicServer\Oic\AbstractEntityFactory')
            ->setMethods(array(
            'createEmptyEntity'
        ))
            ->getMockForAbstractClass();
        
        $factory->expects($this->once())
            ->method('createEmptyEntity')
            ->will($this->returnValue($entity));
        $factory->setHydrator($hydrator);
        
        $this->assertSame($hydratedEntity, $factory->createEntityFromData($entityData));
    }
    
    // ------------------
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createHydratorMock()
    {
        return $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
    }


    protected function createEntityMock()
    {
        return $this->getMock('InoOicServer\Oic\EntityInterface');
    }
}