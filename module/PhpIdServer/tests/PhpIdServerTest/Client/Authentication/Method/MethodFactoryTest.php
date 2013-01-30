<?php

namespace PhpIdServerTest\Client\Authentication\Method;

use PhpIdServer\Client\Authentication\Method\MethodFactory;


class MethodFactoryTest extends \PHPUnit_Framework_TestCase
{

    const CLASS_DUMMY = 'PhpIdServer\Client\Authentication\Method\Dummy';

    protected $factory = null;


    public function setUp()
    {
        $this->factory = new MethodFactory(
            array(
                'dummy' => array(
                    'class' => self::CLASS_DUMMY,
                    'options' => array(
                        'foo' => 'bar'
                    )
                ),
                'dummy_with_no_class' => array(),
                'dummy_with_nonexistent_class' => array(
                    'class' => 'NonExistent\Dummy'
                )
            ));
    }


    public function testGetMethodInfo()
    {
        $methodInfo = $this->factory->getMethodInfo('dummy');
        $this->assertInternalType('array', $methodInfo);
        $this->assertSame('PhpIdServer\Client\Authentication\Method\Dummy', $methodInfo['class']);
    }


    public function testCreateMethodWithInvalidMethod()
    {
        $this->setExpectedException(
            'PhpIdServer\Client\Authentication\Method\Exception\InvalidAuthenticationMethodException');
        $method = $this->factory->createMethod('invalid');
    }


    public function testCreateMethodWithNoMethodClass()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\MissingParameterException');
        $method = $this->factory->createMethod('dummy_with_no_class');
    }


    public function testCreateMethodWithNonExistentClass()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\ClassNotFoundException');
        $method = $this->factory->createMethod('dummy_with_nonexistent_class');
    }


    public function testCreateMethodWithDummyClass()
    {
        $method = $this->factory->createMethod('dummy');
        $this->assertInstanceOf(self::CLASS_DUMMY, $method);
    }
}