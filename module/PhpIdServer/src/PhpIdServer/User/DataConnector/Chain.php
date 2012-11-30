<?php

namespace PhpIdServer\User\DataConnector;

use PhpIdServer\User\UserInterface;


class Chain extends AbstractDataConnector
{

    /**
     * Chained data connectors.
     * 
     * @var array
     */
    protected $_connectors = array();


    /**
     * Adds a data connector to the chains.
     * 
     * @param DataConnectorInterface $connector
     */
    public function addDataConnector (DataConnectorInterface $connector)
    {
        $this->_connectors[] = $connector;
    }


    /**
     * Returns all data connectors from the chain.
     * 
     * @return array
     */
    public function getDataConnectors ()
    {
        return $this->_connectors;
    }


    /**
     * Runs the populateUser action on all data connectors.
     * 
     * @param UserInterface $user
     */
    public function populateUser (UserInterface $user)
    {
        foreach ($this->_connectors as $connector) {
            $connector->populateUser($user);
        }
    }
}