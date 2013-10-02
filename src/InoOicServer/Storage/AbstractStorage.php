<?php

namespace InoOicServer\Storage;

use Zend\Stdlib\Parameters;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\SqlInterface;


abstract class AbstractStorage
{

    const OPT_ADAPTER = 'adapter';

    /**
     * Options.
     * @var Parameters
     */
    protected $options;

    /**
     * DB adapter.
     * @var Adapter
     */
    protected $dbAdapter;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     * @param Adapter $dbAdapter
     */
    public function __construct(Adapter $dbAdapter, $options = array())
    {
        $this->setOptions($options);
        if (null !== $dbAdapter) {
            $this->setAdapter($dbAdapter);
        }
    }


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions($options)
    {
        $this->options = new Parameters($options);
    }


    /**
     * Returns the options.
     * 
     * @return Parameters
     */
    public function getOptions()
    {
        return $this->options;
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
        /*
        $driver = $this->dbAdapter->getDriver();
        $driver->getConnection()->connect();
        $this->dbAdapter->getPlatform()->setDriver($driver);
        */
        /* --- */
    }


    /**
     * Returns the SQL abstraction object.
     *
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->dbAdapter;
    }


    /**
     * Creates a SQL abstraction object based on the provided DB adapter.
     *
     * @param Adapter $dbAdapter
     * @return Sql
     */
    protected function createSql(Adapter $dbAdapter = null)
    {
        if (null === $dbAdapter) {
            $dbAdapter = $this->getAdapter();
        }
        
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
        $sqlString = $this->getSql()->getSqlStringForSqlObject($sqlObject);
        $result = $this->dbAdapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        
        return $result;
    }


    /**
     * "Shortcut" for starting a transaction.
     *
     * @param Adapter $adapter
     */
    protected function beginTransaction(Adapter $adapter)
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
    protected function commit(Adapter $adapter)
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
    protected function rollback(Adapter $adapter)
    {
        $adapter->getDriver()
            ->getConnection()
            ->rollback();
    }
}