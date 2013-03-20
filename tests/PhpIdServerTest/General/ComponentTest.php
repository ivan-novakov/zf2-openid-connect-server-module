<?php

namespace PhpIdServerTest\General;


class ComponentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Component
     */
    protected $_comp = null;


    public function setUp ()
    {
        $this->_comp = $this->_getComponentMock();
    }


    public function testConstructorNoArg ()
    {
        $this->assertSame(array(), $this->_comp->getOptions());
    }


    public function testConstructorWithArray ()
    {
        $options = array(
            'foo' => 'bar'
        );
        $comp = $this->_getComponentMock($options);
        $this->assertSame($options, $comp->getOptions());
    }


    public function testConstructorWithTraversable ()
    {
        $options = new \ArrayObject(array(
            'foo' => 'bar'
        ));
        $comp = $this->_getComponentMocK($options);
        $this->assertSame((array) $options, $comp->getOptions());
    }


    public function testSetOptions ()
    {
        $options = array(
            'foo' => 'bar'
        );
        $this->_comp->setOptions($options);
        $this->assertSame($options, $this->_comp->getOptions());
    }


    public function testSetOption ()
    {
        $this->assertNull($this->_comp->getOption('foo'));
        $this->_comp->setOption('foo', 'bar');
        $this->assertSame('bar', $this->_comp->getOption('foo'));
    }


    protected function _getComponentMock ($options = null)
    {
        return $this->getMockForAbstractClass('PhpIdServer\General\Component', array(
            $options
        ));
    }
}

