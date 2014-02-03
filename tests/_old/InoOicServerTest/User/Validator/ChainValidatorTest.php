<?php

namespace InoOicServerTest\User\Validator;

use InoOicServer\User\Validator\ChainValidator;


class ChainValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ChainValidator
     */
    protected $validator;


    public function setUp()
    {
        $this->validator = new ChainValidator();
    }


    public function testgetValidatorsInitial()
    {
        $this->assertSame(array(), $this->validator->getValidators());
    }


    public function testAddValidator()
    {
        $validator1 = $this->getValidatorMock();
        $this->validator->addValidator($validator1);
        $this->assertEquals(array(
            $validator1
        ), $this->validator->getValidators());
    }


    public function testValidate()
    {
        $user = $this->getMock('InoOicServer\User\UserInterface');
        
        $validator1 = $this->getValidatorMock();
        $validator1->expects($this->once())
            ->method('validate')
            ->with($user);
        
        $validator2 = $this->getValidatorMock();
        $validator2->expects($this->once())
            ->method('validate')
            ->with($user);
        
        $this->validator->addValidator($validator1);
        $this->validator->addValidator($validator2);
        
        $this->validator->validate($user);
    }
    
    /*
     * 
     */
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getValidatorMock()
    {
        $validator = $this->getMock('InoOicServer\User\Validator\ValidatorInterface');
        return $validator;
    }
}