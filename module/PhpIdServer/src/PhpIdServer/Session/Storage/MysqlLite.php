<?php

namespace PhpIdServer\Session\Storage;

use PhpIdServer\Session\Session;


class MysqlLite extends AbstractStorage
{

    const TABLE_NAME = 'session';

    /**
     * The SQL abstraction object.
     * 
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $_dbAdapter = NULL;


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadSessionById()
     */
    public function loadSessionById ($sessionId)
    {}


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadSessionByAccessToken()
     */
    public function loadSessionByAccessToken ($accessToken)
    {}


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveSession()
     */
    public function saveSession (Session $session)
    {
        $sessions = $this->_getSessionsByUserClient($session->getUserId(), $session->getClientId());
        
        $adapter = $this->_getAdapter();
        $sql = $this->_getSql($adapter);
        $insert = $sql->insert();
        
        _dump($session->toArray());
        
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::deleteSession()
     */
    public function deleteSession (Session $session)
    {}


    protected function _getSessionsByUserClient ($userId, $clientId)
    {
        $adapter = $this->_getAdapter();
        $sql = $this->_getSql($adapter);
        $select = $sql->select();
        
        $select->where(array(
            'user_id' => $userId, 
            'client_id' => $clientId
        ));
        
        $selectString = $sql->getSqlStringForSqlObject($select);
        $result = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        _dump($result);
    }
    
    
    protected function _deleteSessionById($sessionId)
    {
        
    }


    /**
     * Returns the SQL abstraction object.
     * 
     * @return \Zend\Db\Sql\Sql
     */
    protected function _getAdapter ()
    {
        if (! ($this->_dbAdapter instanceof \Zend\Db\Adapter\Adapter)) {
            $this->_dbAdapter = new \Zend\Db\Adapter\Adapter($this->_options->get('adapter'));
        }
        
        return $this->_dbAdapter;
    }


    protected function _getSql (\Zend\Db\Adapter\Adapter $adapter, $tableName = NULL)
    {
        if (! $tableName) {
            $tableName = self::TABLE_NAME;
        }
        return new \Zend\Db\Sql\Sql($adapter, $tableName);
    }
}