<?php

namespace InoOicServer\Db;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\Adapter\Adapter as DbAdapter;
use InoOicServer\Oic\EntityFactoryInterface;


abstract class AbstractMapper
{

    /**
     * @var DbAdapter
     */
    protected $dbAdapter;

    /**
     * @var EntityFactoryInterface
     */
    protected $factory;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var Sql
     */
    protected $sql;


    /**
     * Constructor.
     * 
     * @param DbAdapter $dbAdapter
     */
    public function __construct(DbAdapter $dbAdapter, EntityFactoryInterface $factory, HydratorInterface $hydrator)
    {
        $this->setDbAdapter($dbAdapter);
        $this->setFactory($factory);
        $this->setHydrator($hydrator);
    }


    /**
     * @return DbAdapter
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }


    /**
     * @param DbAdapterr $dbAdapter
     */
    public function setDbAdapter(DbAdapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }


    /**
     * @return EntityFactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }


    /**
     * @param EntityFactoryInterface $factory
     */
    public function setFactory(EntityFactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }


    /**
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }


    /**
     * @return Sql
     */
    public function getSql()
    {
        if (! $this->sql instanceof Sql) {
            $this->sql = new Sql($this->getDbAdapter());
        }
        
        return $this->sql;
    }


    public function executeSingleEntityQuery(Select $select, array $params = array())
    {
        $results = $this->executeSelect($select, $params);
        
        if (! $results->count()) {
            return null;
        }
        
        if ($results->count() > 1) {
            throw new \RuntimeException(sprintf("Expected only one record, %d records has been returned", $results->count()));
        }
        
        $data = $results->current();
        $entity = $this->getHydrator()->hydrate($data, $this->getFactory()
            ->createEmptyEntity());
        
        return $entity;
    }


    public function executeSelect(Select $select, array $params = array())
    {
        $statement = $this->getSql()->prepareStatementForSqlObject($select);
        $results = $statement->execute($params);
        
        return $results;
    }
}