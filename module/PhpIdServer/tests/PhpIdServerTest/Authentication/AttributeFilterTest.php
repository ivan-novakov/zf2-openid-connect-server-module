<?php

namespace PhpIdServerTest\Authentication;

use PhpIdServer\Authentication\AttributeFilter;
use PhpIdServer\Authentication\Exception\CreateInputFilterException;


class AttributeFilterTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithBadData()
    {
        $this->setExpectedException('PhpIdServer\Authentication\Exception\CreateInputFilterException');
        
        $factory = $this->getMock('Zend\InputFilter\Factory');
        $factory->expects($this->once())
            ->method('createInputFilter')
            ->will($this->throwException(new CreateInputFilterException()));
        
        $filter = new AttributeFilter(array(), $factory);
    }


    public function testValidate()
    {
        $this->setExpectedException('PhpIdServer\Authentication\Exception\InvalidInputException', 'Invalid input: ["message1","message2"]');
        
        $attributes = array(
            'foo1' => 'bar1', 
            'foo2' => 'bar2'
        );
        
        $messages = array(
            'message1', 
            'message2'
        );
        
        $inputFilter = $this->getMock('Zend\InputFilter\InputFilterInterface');
        $inputFilter->expects($this->once())
            ->method('setData')
            ->with($attributes);
        $inputFilter->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        $inputFilter->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue($messages));
        
        $factory = $this->getMock('Zend\InputFilter\Factory');
        $factory->expects($this->once())
            ->method('createInputFilter')
            ->will($this->returnValue($inputFilter));
        
        $filter = new AttributeFilter(array(), $factory);
        $filter->validate($attributes);
    }
}