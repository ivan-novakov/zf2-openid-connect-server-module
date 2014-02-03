<?php

namespace InoOicServerTest\User\DataConnector;

use InoOicServer\User\DataConnector\DataConnectorFactory;


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
        $this->setExpectedException('InoOicServer\General\Exception\MissingConfigException');
        $this->_factory->createDataConnector(array());
    }


    public function testCreateConnectorWithNonExistentClass ()
    {
        $this->setExpectedException('InoOicServer\General\Exception\InvalidClassException');
        $this->_factory->createDataConnector(array(
            'class' => 'None'
        ));
    }


    public function testCreateConnectorDummy ()
    {
        $connector = $this->_factory->createDataConnector(array(
            'class' => 'InoOicServer\User\DataConnector\Dummy'
        ));
        
        $this->assertInstanceOf('InoOicServer\User\DataConnector\Dummy', $connector);
    }
}