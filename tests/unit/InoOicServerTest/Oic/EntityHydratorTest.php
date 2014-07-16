<?php

namespace InoOicServerTest\Oic;

use InoOicServer\Oic\EntityHydrator;


class EntityHydratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EntityHydrator
     */
    protected $hydrator;


    public function setUp()
    {
        $this->hydrator = $this->getMockBuilder('InoOicServer\Oic\EntityHydrator')->getMockForAbstractClass();
    }


    public function testToDbDateTimeString()
    {
        $dtString = '2010-05-06 07:08:09';
        $this->assertSame($dtString, $this->hydrator->toDbDateTimeString(new \DateTime($dtString)));
    }


    public function testConvertDateTime()
    {
        $time1 = '2010-01-02 03:04:05';
        $time2 = '2010-06-07 08:09:10';
        
        $values = array(
            'foo1' => 'bar1',
            'dt1' => new \DateTime($time1),
            'dt2' => new \DateTime($time2),
            'foo2' => 'bar2'
        );
        
        $expectedValues = array(
            'foo1' => 'bar1',
            'dt1' => $time1,
            'dt2' => $time2,
            'foo2' => 'bar2'
        );
        
        $this->assertEquals($expectedValues, $this->hydrator->convertDateTimeValues($values));
    }


    public function testUnsetFields()
    {
        $values = array(
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
            'foo4' => 'bar4',
            'foo5' => 'bar5'
        );
        
        $unsetFields = array(
            'foo2',
            'foo5'
        );
        
        $expected = array(
            'foo1' => 'bar1',
            'foo3' => 'bar3',
            'foo4' => 'bar4'
        );
        
        $this->assertEquals($expected, $this->hydrator->unsetFields($values, $unsetFields));
    }
}