<?php

namespace PhpIdServer\Session\Storage;

use Zend\Db;
use PhpIdServer\Entity\Entity;
use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\Session\Token\RefreshToken;
use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Session\SessionHydrator;
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
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadSession()
     */
    public function loadSession ($sessionId)
    {
        $adapter = $this->_getAdapter();
        $sql = $this->_getSql($adapter);
        
        $select = $sql->select();
        $select->where(array(
            Session::FIELD_ID => $sessionId
        ));
        
        $result = $this->_sqlQuery($adapter, $sql, $select);
        return $this->_createSessionFromResult($result);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveSession()
     */
    public function saveSession (Session $session)
    {
        $adapter = $this->_getAdapter();
        try {
            $this->_beginTransaction($adapter);
            
            $sql = $this->_getSql($adapter);
            $insert = $sql->insert();
            $insert->values($session->toArray());
            
            $this->_sqlQuery($adapter, $sql, $insert);
            $this->_commit($adapter);
        } catch (\Exception $e) {
            $this->_rollback($adapter);
            throw new Exception\SaveSessionException(sprintf("Error saving session ID '%s': [%s] %s", $session->getId(), get_class($e), $e->getMessage()), 0, $e);
        }
    }


    public function loadAuthorizationCode ($code)
    {}


    public function saveAuthorizationCode (AuthorizationCode $authorizationCode)
    {
        $adapter = $this->_getAdapter();
        try {
            
            $this->_commit($adapter);
        } catch (\Exception $e) {
            $this->_rollback($adapter);
        }
    }


    public function deleteAuthorizationCode (AuthorizationCode $authorizationCode)
    {}


    public function loadAccessToken ($code)
    {}


    public function saveAccessToken (AccessToken $accessToken)
    {}


    public function loadRefreshToken ($code)
    {}


    public function saveRefreshToken (RefreshToken $refreshToken)
    {}
    
    //--------------------------------------------------------------------------------
    

    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadSessionById()
     */
    public function loadSessionById ($sessionId)
    {
        $adapter = $this->_getAdapter();
        $sql = $this->_getSql($adapter);
        
        $select = $sql->select();
        $select->where(array(
            Session::FIELD_ID => $sessionId
        ));
        
        $result = $this->_sqlQuery($adapter, $sql, $select);
        return $this->_createSessionFromResult($result);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadSessionByAccessToken()
     */
    public function loadSessionByAccessToken ($accessToken)
    {}


    public function loadSessionByCode ($code)
    {}


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveSession()
     */
    public function __saveSession (Session $session)
    {
        $adapter = $this->_getAdapter();
        try {
            $this->_beginTransaction($adapter);
            
            /*
             * Check, if there exists a session with the same ID.
             */
            $existingSession = $this->loadSessionById($session->getId());
            if ($existingSession) {
                throw new Exception\SessionExistsException($session->getId());
            }
            
            /*
             * Check if there exists a session for the same pair user ID/client ID.
             */
            $similarSession = $this->loadSessionByUserClient($session->getUserId(), $session->getClientId());
            if ($similarSession) {
                throw new Exception\SimilarSessionExistsException($session->getUserId(), $session->getClientId());
            }
            
            $sql = $this->_getSql($adapter);
            $insert = $sql->insert();
            $insert->values($session->toArray());
            
            $this->_sqlQuery($adapter, $sql, $insert);
            $this->_commit($adapter);
        } catch (\Exception $e) {
            $this->_rollback($adapter);
            throw new Exception\SaveSessionException(sprintf("Error saving session ID '%s': [%s] %s", $session->getId(), get_class($e), $e->getMessage()), 0, $e);
        }
    }


    public function updateSession (Session $session)
    {}


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::deleteSession()
     */
    public function deleteSession (Session $session)
    {}


    /**
     * Loads and returns a session for the supplied user ID and client ID.
     * 
     * @param string $userId
     * @param string $clientId
     * @return Session
     */
    public function loadSessionByUserClient ($userId, $clientId)
    {
        $adapter = $this->_getAdapter();
        $sql = $this->_getSql($adapter);
        $select = $sql->select();
        
        $select->where(array(
            Session::FIELD_USER_ID => $userId, 
            Session::FIELD_CLIENT_ID => $clientId
        ));
        
        $result = $this->_sqlQuery($adapter, $sql, $select);
        return $this->_createSessionFromResult($result);
    }


    protected function _deleteSessionById ($sessionId)
    {}


    protected function _createSessionFromResult (Db\ResultSet\ResultSet $result)
    {
        if (! $result->count()) {
            return NULL;
        }
        
        return $this->_arrayToSession((array) $result->current());
    }


    /**
     * Converts a session object into array representation.
     * 
     * @param Session $session
     * @return array
     */
    protected function _sessionToArray (Session $session)
    {
        $hydrator = $this->getSessionHydrator();
        return $hydrator->extractData($session);
    }


    /**
     * Creates a session object from array.
     * 
     * @param array $data
     * @return Session
     */
    protected function _arrayToSession (Array $data)
    {
        $hydrator = $this->getSessionHydrator();
        $session = new Session();
        
        $hydrator->hydrateObject($data, $session);
        
        return $session;
    }


    /**
     * Returns the SQL abstraction object.
     * 
     * @return \Zend\Db\Adapter\Adapter
     */
    protected function _getAdapter ()
    {
        if (! ($this->_dbAdapter instanceof \Zend\Db\Adapter\Adapter)) {
            $this->_dbAdapter = new \Zend\Db\Adapter\Adapter($this->_options->get('adapter'));
        }
        
        return $this->_dbAdapter;
    }


    /**
     * Returns the SQL abstraction object.
     * 
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @return \Zend\Db\Sql\Sql
     */
    protected function _getSql (\Zend\Db\Adapter\Adapter $adapter)
    {
        $tableName = $this->_getTableName();
        
        return new \Zend\Db\Sql\Sql($adapter, $tableName);
    }


    /**
     * Returns the table namem, where the sessions are stored.
     * 
     * @param string $defaultValue
     * @return string
     */
    protected function _getTableName ($defaultValue = 'undefined')
    {
        return $this->_options->get('table', $defaultValue);
    }


    /**
     * "Shortcut" for starting a transaction.
     * 
     * @param \Zend\Db\Adapter\Adapter $adapter
     */
    protected function _beginTransaction (\Zend\Db\Adapter\Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->beginTransaction();
    }


    /**
     * "Shortcut" for commiting a transaction.
     * 
     * @param \Zend\Db\Adapter\Adapter $adapter
     */
    protected function _commit (\Zend\Db\Adapter\Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->commit();
    }


    /**
     * "Shortcut" for rolling back a transaction.
     * 
     * @param \Zend\Db\Adapter\Adapter $adapter
     */
    protected function _rollback (\Zend\Db\Adapter\Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->rollback();
    }


    /**
     * "Shortcut" for executing queries.
     * 
     * @param Db\Adapter\Adapter $adapter
     * @param Db\Sql\Sql $sql
     * @param Db\Sql\SqlInterface $sqlObject
     * @return Db\ResultSet\Zend\Db\ResultSet
     */
    protected function _sqlQuery (Db\Adapter\Adapter $adapter, Db\Sql\Sql $sql, Db\Sql\SqlInterface $sqlObject)
    {
        return $adapter->query($sql->getSqlStringForSqlObject($sqlObject), $adapter::QUERY_MODE_EXECUTE);
    }
}