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
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\SqlInterface;


class MysqlLite extends AbstractStorage
{

    const OPT_ADAPTER = 'adapter';

    const OPT_SESSION_TABLE = 'session_table';

    const OPT_AUTHORIZATION_CODE_TABLE = 'authorization_code_table';

    const OPT_ACCESS_TOKEN_TABLE = 'access_token_table';

    const OPT_REFRESH_TOKEN_TABLE = 'refresh_token_table';

    /**
     * DB adapter.
     * 
     * @var Adapter
     */
    protected $dbAdapter = NULL;

    /**
     * SQL abstraction.
     * 
     * @var Sql
     */
    protected $sql = NULL;


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadSession()
     * @return Session
     */
    public function loadSession($sessionId)
    {
        $sql = $this->getSql();
        
        $select = $sql->select($this->_getSessionTableName());
        $select->where(array(
            Session::FIELD_ID => $sessionId
        ));
        
        $result = $this->executeSqlQuery($select);
        return $this->_createSessionFromResult($result);
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveSession()
     */
    public function saveSession(Session $session)
    {
        try {
            //$this->_beginTransaction($adapter);
            

            $sql = $this->getSql();
            $insert = $sql->insert($this->_getSessionTableName());
            $insert->values($this->_sessionToArray($session));
            
            $result = $this->executeSqlQuery($insert);
            //$this->_commit($adapter);
        } catch (\Exception $e) {
            //$this->_rollback($adapter);
            throw new Exception\SaveException($session, $e);
        }
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadAuthorizationCode()
     * @return AuthorizationCode
     */
    public function loadAuthorizationCode($code)
    {
        $sql = $this->getSql();
        
        $select = $sql->select($this->_getAuthoriozationCodeTableName());
        $select->where(array(
            AuthorizationCode::FIELD_CODE => $code
        ));
        
        $result = $this->executeSqlQuery($select);
        if (! $result->count()) {
            return NULL;
        }
        
        $data = (array) $result->current();
        $authorizationCode = new AuthorizationCode();
        
        return $this->getAuthorizationCodeHydrator()
            ->hydrateObject($data, $authorizationCode);
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveAuthorizationCode()
     */
    public function saveAuthorizationCode(AuthorizationCode $authorizationCode)
    {
        try {
            $sql = $this->getSql();
            $insert = $sql->insert($this->_getAuthoriozationCodeTableName());
            $insert->values($this->getAuthorizationCodeHydrator()
                ->extract($authorizationCode));
            
            $result = $this->executeSqlQuery($insert);
        } catch (\Exception $e) {
            throw new Exception\SaveException($authorizationCode, $e);
        }
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::deleteAuthorizationCode()
     * @return AuthorizationCode
     */
    public function deleteAuthorizationCode(AuthorizationCode $authorizationCode)
    {
        $sql = $this->getSql();
        $delete = $sql->delete($this->_getAuthoriozationCodeTableName());
        
        $delete->where(array(
            AuthorizationCode::FIELD_CODE => $authorizationCode->getCode()
        ));
        
        $result = $this->executeSqlQuery($delete);
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::loadAccessToken()
     * @return AccessToken
     */
    public function loadAccessToken($token)
    {
        $sql = $this->getSql();
        
        $select = $sql->select($this->_getAccessTokenTableName());
        $select->where(array(
            AccessToken::FIELD_TOKEN => $token
        ));
        
        $result = $this->executeSqlQuery($select);
        if (! $result->count()) {
            return NULL;
        }
        
        $data = (array) $result->current();
        $accessToken = new AccessToken();
        
        return $this->getAccessTokenHydrator()
            ->hydrate($data, $accessToken);
    }


    /**
     * {@inheritdoc}
     * @see \PhpIdServer\Session\Storage\StorageInterface::saveAccessToken()
     */
    public function saveAccessToken(AccessToken $accessToken)
    {
        try {
            $sql = $this->getSql();
            
            $insert = $sql->insert($this->_getAccessTokenTableName());
            $insert->values($this->getAccessTokenHydrator()
                ->extract($accessToken));
            
            $result = $this->executeSqlQuery($insert);
        } catch (\Exception $e) {
            throw new Exception\SaveException($accessToken, $e);
        }
    }


    public function loadRefreshToken($code)
    {}


    public function saveRefreshToken(RefreshToken $refreshToken)
    {}


    protected function _createSessionFromResult(Db\ResultSet\ResultSet $result)
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
    protected function _sessionToArray(Session $session)
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
    protected function _arrayToSession(Array $data)
    {
        $hydrator = $this->getSessionHydrator();
        $session = new Session();
        
        $hydrator->hydrateObject($data, $session);
        
        return $session;
    }


    protected function _getSessionTableName()
    {
        return $this->_options->get(self::OPT_SESSION_TABLE);
    }


    protected function _getAuthoriozationCodeTableName()
    {
        return $this->_options->get(self::OPT_AUTHORIZATION_CODE_TABLE);
    }


    protected function _getAccessTokenTableName()
    {
        return $this->_options->get(self::OPT_ACCESS_TOKEN_TABLE);
    }


    protected function _getRefreshTokenTableName()
    {
        return $this->_options->get(self::OPT_REFRESH_TOKEN_TABLE);
    }


    /**
     * "Shortcut" for starting a transaction.
     * 
     * @param Adapter $adapter
     */
    protected function _beginTransaction(\Zend\Db\Adapter\Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->beginTransaction();
    }


    /**
     * "Shortcut" for commiting a transaction.
     * 
     * @param Adapter $adapter
     */
    protected function _commit(\Zend\Db\Adapter\Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->commit();
    }


    /**
     * "Shortcut" for rolling back a transaction.
     * 
     * @param Adapter $adapter
     */
    protected function _rollback(\Zend\Db\Adapter\Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->rollback();
    }


    /**
     * Sets the DB adapter.
     * 
     * @param Adapter $dbAdapter
     */
    public function setAdapter(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        
        /*
         * temp fix
         */
        $driver = $this->dbAdapter->getDriver();
        $driver->getConnection()
            ->connect();
        $this->dbAdapter->getPlatform()
            ->setDriver($driver);
        /* --- */
        
        $this->setSqlFromAdapter($dbAdapter);
    }


    /**
     * Returns the SQL abstraction object.
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        if (! ($this->dbAdapter instanceof Adapter)) {
            $this->setAdapter($this->createAdapter());
        }
        
        return $this->dbAdapter;
    }


    /**
     * Returns the SQL abstraction object.
     * 
     * @return Sql
     */
    public function getSql()
    {
        if (! ($this->sql instanceof Sql)) {
            $this->setSqlFromAdapter($this->getAdapter());
        }
        return $this->sql;
    }


    /**
     * Sets the SQL abstraction object.
     * 
     * @param Sql $sql
     */
    public function setSql(Sql $sql)
    {
        $this->sql = $sql;
    }


    /**
     * Sets the SQL abstraction object for the corresponding DB adapter.
     * 
     * @param Adapter $dbAdapter
     */
    public function setSqlFromAdapter(Adapter $dbAdapter)
    {
        $this->setSql($this->createSql($dbAdapter));
    }


    /**
     * Creates a DB adapter based on the passed options (or the configured options).
     * 
     * @param array $dbOptions
     * @return Adapter
     */
    protected function createAdapter(array $dbOptions = null)
    {
        if (null === $dbOptions) {
            $dbOptions = $this->_options->get(self::OPT_ADAPTER);
        }
        
        return new Adapter($dbOptions);
    }


    /**
     * Creates a SQL abstraction object based on the provided DB adapter.
     * 
     * @param Adapter $dbAdapter
     * @return Sql
     */
    protected function createSql(Adapter $dbAdapter)
    {
        return new Sql($dbAdapter);
    }


    /**
     * Executes the SQL object and returns the result.
     * 
     * @param SqlInterface $sqlObject
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\Zend\Db\ResultSet|\Zend\Db\Adapter\Driver\ResultInterface|\Zend\Db\ResultSet\Zend\Db\ResultSetInterface
     */
    protected function executeSqlQuery(SqlInterface $sqlObject)
    {
        $sqlString = $this->getSql()
            ->getSqlStringForSqlObject($sqlObject);
        $result = $this->dbAdapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        
        return $result;
    }
}