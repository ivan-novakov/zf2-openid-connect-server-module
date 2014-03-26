<?php

namespace InoOicServerTest\Util;

use DateTime;


class ConvertToDateTimeTraitTest extends \PHPUnit_Framework_TestCase
{

    protected $trait;


    public function setUp()
    {
        $this->trait = $this->getObjectForTrait('InoOicServer\Util\ConvertToDateTimeTrait');
    }


    public function testConvertDateTime()
    {
        $value = new DateTime();
        $this->assertSame($value, $this->trait->convertToDateTime($value));
    }


    public function testConvertInvalidValue()
    {
        $this->setExpectedException('InoOicServer\Util\Exception\InvalidDateTimeValueException', 'Invalid value type');
        
        $value = array();
        $this->trait->convertToDateTime($value);
    }


    public function testConvertValidString()
    {
        $value = '2014-01-01';
        $expected = new DateTime($value);
        
        $this->assertEquals($expected, $this->trait->convertToDateTime($value));
    }


    public function testConvertInvalidString()
    {
        $this->setExpectedException('InoOicServer\Util\Exception\InvalidDateTimeValueException', 'Invalid datetime string');
        
        $value = 'invalidstring';
        $this->trait->convertToDateTime($value);
    }
}