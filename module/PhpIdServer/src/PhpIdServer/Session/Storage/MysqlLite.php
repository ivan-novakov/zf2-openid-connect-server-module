<?php

namespace PhpIdServer\Session\Storage;

use PhpIdServer\Session\Token\AuthrozationCode;
use Zend\Db;
use PhpIdServer\Entity\Entity;
use PhpIdServer\Session\Token\AccessToken;
use PhpIdServer\Session\Token\RefreshToken;
use PhpIdServer\Session\Token\AuthorizationCode;
use PhpIdServer\Session\SessionHydrator;
use PhpIdServer\Session\Session;


class MysqlLite extends AbstractStorage
{

    const OPT_ADAPTER = 'adapter';

    const OPT_SESSION_TABLE = 'session_table';

    const OPT_AUTHORIZATION_CODE_TABLE = 'authorization_code_table';

    const OPT_ACCESS_TOKEN_TABLE = 'access_token_table';

    const OPT_REFRESH_TOKEN_TABLE = 'refresh_token_table';

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
        
        $select = $sql->select($this->_getSessionTableName());
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
            //$this->_beginTransaction($adapter);
            

            $sql = $this->_getSql($adapter);
            $insert = $sql->insert($this->_getSessionTableName());
            $insert->values($this->_sessionToArray($session));
            
            $this->_sqlQuery($adapter, $sql, $insert);
            //$this->_commit($adapter);
        } catch (\Exception $e) {
            //$this->_rollback($adapter);
            throw new Exception\SaveException($session, $e);
        }
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadAuthorizationCode()
     */
    public function loadAuthorizationCode ($code)
    {
        $adapter = $this->_getAdapter();
        $sql = $this->_getSql($adapter);
        
        $select = $sql->select($this->_getAuthoriozationCodeTableName());
        $select->where(array(
            AuthorizationCode::FIELD_CODE => $code
        ));
        
        $result = $this->_sqlQuery($adapter, $sql, $select);
        if (! $result->count()) {
            return NULL;
        }
        
        $data = (array) $result->current();
        $authorizationCode = new AuthorizationCode();
        
        return $this->getAuthorizationCodeHydrator()
            ->hydrateObject($data, $authorizationCode);
    }


    /**
     * (non-PHPdoc)
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveAuthorizationCode()
     */
    public function saveAuthorizationCode (AuthorizationCode $authorizationCode)
    {
        $adapter = $this->_getAdapter();
        try {
            
            $sql = $this->_getSql($adapter);
            $insert = $sql->insert($this->_getAuthoriozationCodeTableName());
            $insert->values($this->getAuthorizationCodeHydrator()
                ->extract($authorizationCode));
            
            $this->_sqlQuery($adapter, $sql, $insert);
        } catch (\Exception $e) {
            throw new Exception\SaveException($authorizationCode, $e);
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
            $this->_dbAdapter = new \Zend\Db\Adapter\Adapter($this->_options->get(self::OPT_ADAPTER));
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
        return new \Zend\Db\Sql\Sql($adapter);
    }


    protected function _getSessionTableName ()
    {
        return $this->_options->get(self::OPT_SESSION_TABLE);
    }


    protected function _getAuthoriozationCodeTableName ()
    {
        return $this->_options->get(self::OPT_AUTHORIZATION_CODE_TABLE);
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