<?php

namespace InoOicServerTest\User\Validator;

use InoOicServer\User\Validator\ValidatorFactory;


class ValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = new ValidatorFactory();
    }


    public function testCreateValidatorWithNoClass()
    {
        $this->setExpectedException('InoOicServer\General\Exception\MissingConfigException');
        
        $this->factory->createValidator(array());
    }


    public function testCreateValidatorWithNonExistentClass()
    {
        $this->setExpectedException('InoOicServer\General\Exception\InvalidClassException');
        
        $this->factory->createValidator(array(
            'class' => 'foo'
        ));
    }


    public function testCreateValidator()
    {
        $validator = $this->factory->createValidator(array(
            'class' => 'InoOicServer\User\Validator\Dummy'
        ));
        
        $this->assertInstanceOf('InoOicServer\User\Validator\ValidatorInterface', $validator);
    }
}