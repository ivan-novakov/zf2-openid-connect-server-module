<?php

namespace InoOicServer\Util\OptionsTrait;

use InoOicServer\Util\Options;


class OptionsTraitTest extends \PHPUnit_Framework_TestCase
{

    protected $trait;


    public function setUp()
    {
        $this->trait = $this->getObjectForTrait('InoOicServer\Util\OptionsTrait');
    }


    public function testSetDefaultOptions()
    {
        $defaultOptions = array(
            'foo1' => 'bar1',
            'foo2' => 'bar2'
        );
        
        $this->trait->setDefaultOptions($defaultOptions);
        $this->assertSame($defaultOptions, $this->trait->getDefaultOptions());
    }


    public function testSetOptionsWithInvalidArgument()
    {
        $this->setExpectedException('Zend\Stdlib\Exception\InvalidArgumentException', 'Options must be an array or Traversable');
        
        $this->trait->setOptions('invalid');
    }


    public function testSetOptionsWithArray()
    {
        $options = array(
            'foo1' => 'bar1',
            'foo2' => 'bar2'
        );
        
        $this->trait->setOptions($options);
        
        $this->assertEquals($options, (array) $this->trait->getOptions());
    }


    public function testSetOptionsWithOptions()
    {
        $options = new Options(array(
            'foo1' => 'bar1',
            'foo2' => 'bar2'
        ));
        
        $this->trait->setOptions($options);
        
        $this->assertEquals($options, $this->trait->getOptions());
    }


    public function testSetOptionsWithDefaultOptions()
    {
        $defaultOptions = array(
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3'
        );
        $this->trait->setDefaultOptions($defaultOptions);
        
        $options = array(
            'foo2' => 'bar21'
        );
        
        $this->trait->setOptions($options);
        
        $expected = array(
            'foo1' => 'bar1',
            'foo2' => 'bar21',
            'foo3' => 'bar3'
        );
        
        $this->assertSame($expected, (array) $this->trait->getOptions());
    }


    public function testGetOption()
    {
        $this->assertNull($this->trait->getOption('foo'));
        
        $this->trait->setOptions(array(
            'foo' => 'bar'
        ));
        
        $this->assertSame('bar', $this->trait->getOption('foo'));
    }


    public function testGetOptionWithDefaultValue()
    {
        $this->assertSame('def', $this->trait->getOption('foo', 'def'));
    }
}

