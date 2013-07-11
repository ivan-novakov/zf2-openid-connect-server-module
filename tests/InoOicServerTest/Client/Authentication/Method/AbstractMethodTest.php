<?php

namespace InoOicServer\Client\Authentication\Method;


class AbstractMethodTest extends \PHPUnit_Framework_Testcase
{


    public function testConstructor()
    {
        $options = array(
            'foo' => 'bar'
        );
        
        $method = $this->getMockBuilder('InoOicServer\Client\Authentication\Method\AbstractMethod')
            ->setConstructorArgs(array(
            $options
        ))
            ->getMockForAbstractClass();
        
        $this->assertSame($options, (array) $method->getOptions());
    }


    public function testSetOptions()
    {
        $options = array(
            'foo' => 'bar'
        );
        $method = $this->getMockForAbstractClass('InoOicServer\Client\Authentication\Method\AbstractMethod');
        $this->assertEmpty((array) $method->getOptions());
        $method->setOptions($options);
        $this->assertSame($options, (array) $method->getOptions());
    }


    public function testCreateSuccessResult()
    {
        $method = $this->getMockForAbstractClass('InoOicServer\Client\Authentication\Method\AbstractMethod');
        $result = $method->createSuccessResult();
        $this->assertTrue($result->isAuthenticated());
    }


    public function testCreateFailureResult()
    {
        $method = $this->getMockForAbstractClass('InoOicServer\Client\Authentication\Method\AbstractMethod');
        $result = $method->createFailureResult('some reason');
        $this->assertFalse($result->isAuthenticated());
        $this->assertSame('some reason', $result->getNotAuthenticatedReason());
    }
}