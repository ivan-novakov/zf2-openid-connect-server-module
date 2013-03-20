<?php
namespace PhpIdServer\Util;
use PhpIdServer\Util\Options;


class OptionsTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithArray ()
    {
        $options = new Options(array(
            'foo' => 'bar'
        ));
        
        $this->assertEquals('bar', $options->get('foo'));
    }


    public function testConstructorWithConfig ()
    {
        $options = new Options(new \Zend\Config\Config(array(
            'foo' => 'bar'
        )));
        
        $this->assertEquals('bar', $options->get('foo'));
    }


    public function testInvalidArgumentException ()
    {
        $this->setExpectedException('\Zend\Stdlib\Exception\InvalidArgumentException');
        
        $options = new Options('test');
    }


    public function testSetter ()
    {
        $options = new Options();
        $options->set('foo', 'bar');
        
        $this->assertEquals('bar', $options->get('foo'));
    }
}