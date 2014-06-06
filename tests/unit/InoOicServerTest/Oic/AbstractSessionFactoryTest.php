<?php

namespace InoOicServerTest\Oic;


class AbstractSessionFactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $factory;


    public function setUp()
    {
        $this->factory = $this->getMockBuilder('InoOicServer\Oic\AbstractSessionFactory')
            ->setConstructorArgs(array(
            $this->createHashGeneratorMock()
        ))
            ->getMockForAbstractClass();
    }


    public function testSetHashGenerator()
    {
        $hashGenerator = $this->createHashGeneratorMock();
        $this->factory->setHashGenerator($hashGenerator);
        $this->assertSame($hashGenerator, $this->factory->getHashGenerator());
    }


    public function testGetImplicitDateTimeUtil()
    {
        $this->assertInstanceOf('InoOicServer\Util\DateTimeUtil', $this->factory->getDateTimeUtil());
    }
    
    /*
     * 
     */
    protected function createHashGeneratorMock()
    {
        $generator = $this->getMock('InoOicServer\Crypto\Hash\HashGeneratorInterface');
        
        return $generator;
    }
}