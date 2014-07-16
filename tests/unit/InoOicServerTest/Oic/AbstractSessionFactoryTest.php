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
}