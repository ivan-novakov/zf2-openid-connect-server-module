<?php

namespace InoOicServerTest\Oic;


class AbstractSessionFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = $this->getMockForAbstractClass('InoOicServer\Oic\AbstractSessionFactory');
    }


    public function testGetImplicitDateTimeUtil()
    {
        $this->assertInstanceOf('InoOicServer\Util\DateTimeUtil', $this->factory->getDateTimeUtil());
    }


    public function testGetImplicitHydrator()
    {
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\ClassMethods', $this->factory->getHydrator());
    }
}