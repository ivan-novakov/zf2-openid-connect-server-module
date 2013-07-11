<?php

namespace InoOicServerTest\Client\Authentication\Method;

use InoOicServer\Client\Authentication\Method\MethodFactory;


class MethodFactoryTest extends \PHPUnit_Framework_TestCase
{

    const CLASS_DUMMY = 'InoOicServer\Client\Authentication\Method\Dummy';

    /**
     * @var MethodFactory
     */
    protected $factory;


    public function setUp()
    {
        $this->factory = new MethodFactory();
    }


    public function testCreateMethodWithMissingClass()
    {
        $this->setExpectedException('InoOicServer\General\Exception\MissingParameterException', 
            "Missing value for parameter 'class'");
        
        $this->factory->createAuthenticationMethod(array());
    }


    public function testCreateMethodWithNonExistentClass()
    {
        $this->setExpectedException('InoOicServer\General\Exception\ClassNotFoundException', 
            "Class not found: NonExistentClass");
        
        $this->factory->createAuthenticationMethod(array(
            'class' => 'NonExistentClass'
        ));
    }


    public function testCreateMethod()
    {
        $methodConfig = array(
            'class' => self::CLASS_DUMMY,
            'options' => array(
                'foo' => 'bar'
            )
        );
        
        $method = $this->factory->createAuthenticationMethod($methodConfig);
        $this->assertInstanceOf('InoOicServer\Client\Authentication\Method\MethodINterface', $method);
    }
}