<?php

namespace PhpIdServerTest\Util\Filter;

use PhpIdServer\Util\Filter\ShibbolethSerializedValue;


class ShibbolethSerializedValueTest extends \PHPUnit_Framework_TestCase
{

    protected $filter = null;


    public function setUp()
    {
        $this->filter = new ShibbolethSerializedValue();
    }


    public function testFilterOneValue()
    {
        $this->assertSame('value1', $this->filter->filter('value1'));
    }


    public function testFilterMultipleValues()
    {
        $this->assertSame('value1', $this->filter->filter('value1;value2;value3'));
    }


    public function testFilterNoValue()
    {
        $this->assertSame('', $this->filter->filter(''));
    }
}