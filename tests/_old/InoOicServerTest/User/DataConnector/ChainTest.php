<?php

namespace InoOicServerTest\User\DataConnector;

use InoOicServer\User\DataConnector\Chain;


class ChainTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Chain
     */
    protected $_chain = null;


    public function setUp ()
    {
        $this->_chain = new Chain();
    }


    public function testAddDataConnector ()
    {
        $this->assertEmpty($this->_chain->getDataConnectors());
        $connector = $this->getMock('InoOicServer\User\DataConnector\DataConnectorInterface');
        $this->_chain->addDataConnector($connector);
        $connectors = $this->_chain->getDataConnectors();
        $this->assertInternalType('array', $connectors);
        $this->assertSame($connector, $connectors[0]);
    }


    public function testPopulateUser ()
    {
        $user = $this->getMock('InoOicServer\User\UserInterface');
        $this->_chain->addDataConnector($this->_getConnectorMock($user));
        $this->_chain->addDataConnector($this->_getConnectorMock($user));
        
        $this->_chain->populateUser($user);
    }


    protected function _getConnectorMock ($user)
    {
        $connector = $this->getMock('InoOicServer\User\DataConnector\DataConnectorInterface');
        $connector->expects($this->once())
            ->method('populateUser')
            ->with($user);
        
        return $connector;
    }
}