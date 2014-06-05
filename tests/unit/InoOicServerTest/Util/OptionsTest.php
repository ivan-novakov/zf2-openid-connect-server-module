<?php

namespace InoOicServerTest\Util;

use InoOicServer\Util\Options;


class OptionsTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithArray()
    {
        $options = new Options(array(
            'foo' => 'bar'
        ));
        
        $this->assertEquals('bar', $options->get('foo'));
    }


    public function testGetSetValue()
    {
        $options = new Options();
        $options->set('foo', 'bar');
        
        $this->assertEquals('bar', $options->get('foo'));
    }


    public function testGetDefaultValue()
    {
        $options = new Options();
        $this->assertEquals('default', $options->get('foo', 'default'));
    }


    public function testGetUnsetValue()
    {
        $options = new Options();
        
        $this->assertNull($options->get('foo'));
    }
}