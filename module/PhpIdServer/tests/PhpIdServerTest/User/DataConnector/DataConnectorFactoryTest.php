<?php

namespace PhpIdServerTest\User\DataConnector;

use PhpIdServer\User\DataConnector\DataConnectorFactory;


class DataConnectorFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DataConnectorFactory
     */
    protected $_factory = null;


    public function setUp ()
    {
        $this->_factory = new DataConnectorFactory();
    }


    public function testCreateDataConnectorWithNoClass ()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\MissingConfigException');
        $this->_factory->createDataConnector(array());
    }


    public function testCreateConnectorWithNonExistentClass ()
    {
        $this->setExpectedException('PhpIdServer\General\Exception\InvalidClassException');
        $this->_factory->createDataConnector(array(
            'class' => 'None'
        ));
    }


    public function testCreateConnectorDummy ()
    {
        $connector = $this->_factory->createDataConnector(array(
            'class' => 'PhpIdServer\User\DataConnector\Dummy'
        ));
        
        $this->assertInstanceOf('PhpIdServer\User\DataConnector\Dummy', $connector);
    }
}