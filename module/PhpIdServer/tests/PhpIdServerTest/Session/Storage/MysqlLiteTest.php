<?php

namespace PhpIdServerTest\Session\Storage;

use PhpIdServerTest\Framework\Config;
use PhpIdServer\Session\Session;
use PhpIdServer\Session\Storage;


class MysqlLiteTest extends \PHPUnit_Extensions_Database_TestCase
{

    /**
     * Raw DB PDO connection.
     * 
     * @var \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    protected $_conn = NULL;

    /**
     * Session storage object.
     * @var Storage\MysqlLite
     */
    protected $_storage = NULL;


    /**
     * (non-PHPdoc)
     * @see PHPUnit_Extensions_Database_TestCase::getConnection()
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection ()
    {
        if (NULL === $this->_conn) {
            $pdoConfig = Config::get()->db->pdo;
            
            $dsn = sprintf("%s:dbname=%s;host=%s", $pdoConfig['raw_driver'], $pdoConfig['database'], $pdoConfig['host']);
            $pdo = new \PDO($dsn, $pdoConfig['username'], $pdoConfig['password']);
            
            $this->_conn = $this->createDefaultDBConnection($pdo);
        }
        
        return $this->_conn;
    }


    /**
     * (non-PHPdoc)
     * @see PHPUnit_Extensions_Database_TestCase::getDataSet()
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet ()
    {
        return $this->createFlatXMLDataSet(Config::get()->db->dataset);
    }


    public function setUp ()
    {
        parent::setUp();
        
        $dbConfig = Config::get()->db;
        $this->_storage = new Storage\MysqlLite(array(
            'table' => $dbConfig->table, 
            'adapter' => $dbConfig->pdo->toArray()
        ));
    }


    public function testSaveLoadSession ()
    {
        $session = $this->_createSession();
        
        $this->_storage->saveSession($session);
        
        // FIXME - use data table
        $loadedSession = $this->_storage->loadSession($session->getId());
        
        $this->assertInstanceOf('\PhpIdServer\Session\Session', $loadedSession);
        $this->assertEquals($loadedSession->toArray(), $session->toArray());
    }
    
    /*
    public function testLoadSessionById ()
    {
        $session = $this->_storage->loadSessionById('session_id_456');
        $this->assertInstanceOf('\PhpIdServer\Session\Session', $session);
        $this->assertEquals($session->getId(), 'session_id_456');
    }


    public function testLoadSessionByUserClient ()
    {
        $session = $this->_storage->loadSessionByUserClient('testuser2', 'testclient2');
        $this->assertInstanceOf('\PhpIdServer\Session\Session', $session);
        $this->assertEquals($session->getId(), 'session_id_456');
    }


    public function testSaveSession ()
    {
        $session = $this->_createSession();
        
        $this->_storage->saveSession($session);
        
        // FIXME - use data table
        $loadedSession = $this->_storage->loadSessionById($session->getId());
        
        $this->assertInstanceOf('\PhpIdServer\Session\Session', $loadedSession);
        $this->assertEquals($loadedSession->toArray(), $session->toArray());
    }


    public function testSaveSessionExists ()
    {
        $session = $this->_createSession(array(
            'sessionId' => 'session_id_456'
        ));
        
        try {
            $this->_storage->saveSession($session);
        } catch (Storage\Exception\SaveSessionException $e) {
            $target = $e->getPrevious();
        }
        
        $this->assertInstanceOf('\PhpIdServer\Session\Storage\Exception\SessionExistsException', $target);
    }


    public function testSaveSessionUserClientExists ()
    {
        $session = $this->_createSession(array(
            'userId' => 'testuser2', 
            'clientId' => 'testclient2'
        ));
        
        $target = NULL;
        
        try {
            $this->_storage->saveSession($session);
        } catch (Storage\Exception\SaveSessionException $e) {
            $target = $e->getPrevious();
        }
        
        $this->assertInstanceOf('\PhpIdServer\Session\Storage\Exception\SimilarSessionExistsException', $target);
    }


    protected function _createSession (Array $override = array())
    {
        $sessionId = isset($override['sessionId']) ? $override['sessionId'] : 'session_id_123';
        $userId = isset($override['userId']) ? $override['userId'] : 'testuser';
        $clientId = isset($override['clientId']) ? $override['clientId'] : 'testclient';
        $time = '2012-09-12 00:00:00';
        $method = 'dummy';
        $code = 'authorization_code_123';
        $data = 'serialized_user_data_123';
        $accessToken = 'access_token_123';
        $refreshToken = 'refresh_token_123';
        $ctime = '2012-09-08 00:00:00';
        $mtime = '2012-09-09 00:00:00';
        
        return Session::create($sessionId, $userId, $clientId, $time, $method, $code, $data, $accessToken, $refreshToken, $ctime, $mtime);
    }
    */
    protected function _createSession ()
    {
        $data = array(
            Session::FIELD_ID => 'session_id_123', 
            Session::FIELD_USER_ID => 'testuser', 
            Session::FIELD_AUTHENTICATION_TIME => '2012-08-01 00:00:00', 
            Session::FIELD_AUTHENTICATION_METHOD => 'dummy', 
            Session::FIELD_CREATE_TIME => new \DateTime('now'), 
            Session::FIELD_MODIFY_TIME => '2012-09-09 00:00:00', 
            Session::FIELD_EXPIRATION_TIME => '2012-09-13 00:00:00', 
            Session::FIELD_USER_DATA => 'serialized_user_data_123'
        );
        
        return new Session($data);
    }
}