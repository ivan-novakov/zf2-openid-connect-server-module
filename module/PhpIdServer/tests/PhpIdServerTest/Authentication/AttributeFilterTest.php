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
        $filter->filterValues($attributes);
    }


    /**
     * @dataProvider validationDataProvider
     */
    public function testValidateWithData(array $inputFilterConfig, array $attributes, $isValid, 
        array $expectedFilteredAttributes)
    {
        $filter = new AttributeFilter($inputFilterConfig);
        
        if (! $isValid) {
            $this->setExpectedException('PhpIdServer\Authentication\Exception\InvalidInputException');
        }
        
        $filteredAttributes = $filter->filterValues($attributes);
        $this->assertSame($expectedFilteredAttributes, $filteredAttributes);
    }


    public function validationDataProvider()
    {
        return array(
            
            array(
                // $inputFilterConfig
                array(
                    'eppn' => array(
                        'name' => 'eppn', 
                        'required' => true
                    )
                ), 
                // $attributes
                array(
                    'eppn' => 'testuser', 
                    'mail' => 'testuser@example.org'
                ), 
                // $isValid
                true, 
                // $expectedFilteredAttributes
                array(
                    'eppn' => 'testuser'
                )
            ), 
            
            array(
                // $inputFilterConfig
                array(
                    'eppn' => array(
                        'name' => 'eppn', 
                        'required' => true
                    )
                ), 
                // $attributes
                array(
                    'uid' => 'testuser', 
                    'mail' => 'testuser@example.org'
                ), 
                // $isValid
                false, 
                // $expectedFilteredAttributes
                array()
            ), 
            
            array(
                // $inputFilterConfig
                array(
                    'eppn' => array(
                        'name' => 'eppn', 
                        'required' => true, 
                        'validators' => array(
                            array(
                                'name' => 'email_address'
                            )
                        )
                    ), 
                    'mail' => array(
                        'name' => 'mail', 
                        'required' => true, 
                        'validators' => array(
                            array(
                                'name' => 'email_address'
                            )
                        )
                    ), 
                    'givenname' => array(
                        'name' => 'givenname', 
                        'required' => true
                    ), 
                    'sn' => array(
                        'name' => 'sn', 
                        'required' => true
                    )
                ), 
                // $attributes
                array(
                    'eppn' => 'testuser@example.org', 
                    'mail' => 'testuser@mail.example.org', 
                    'givenname' => 'Test', 
                    'sn' => 'User',
                    'foo' => 'bar'
                ), 
                // $isValid
                true, 
                // $expectedFilteredAttributes
                array(
                    'eppn' => 'testuser@example.org', 
                    'mail' => 'testuser@mail.example.org', 
                    'givenname' => 'Test', 
                    'sn' => 'User'
                )
            )
        );
    }
}