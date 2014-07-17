<?php

namespace InoOicServer\Test\TestCase;

use DateTime;
use Zend\Db;
use Zend\Config\Config;
use InoOicServer\Test\DbUnit\ArrayDataSet;


abstract class AbstractDatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{

    private static $pdo;

    private $conn;

    protected $dbConfig;

    protected $rawTableData;


    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = $this->getPdo();
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $this->getDbName());
        }

        return $this->conn;
    }


    public function getDataSet()
    {
        return $this->createArrayDataSet($this->getRawTableData());
    }


    protected function getRawTableData($tableName = null, $rowNumber = null)
    {
        if (null === $this->rawTableData) {
            $this->rawTableData = $this->createRawTableData();
        }

        if (null !== $tableName) {
            if (isset($this->rawTableData[$tableName])) {
                if (null !== $rowNumber) {
                    if (isset($this->rawTableData[$tableName][$rowNumber])) {
                        return $this->rawTableData[$tableName][$rowNumber];
                    }

                    return array();
                }
                return $this->rawTableData[$tableName];
            }

            return array();
        }

        return $this->rawTableData;
    }


    protected function createRawTableData()
    {
        return array();
    }


    /**
     * Workaround for https://github.com/sebastianbergmann/dbunit/issues/37.
     *
     * @see https://github.com/sebastianbergmann/dbunit/issues/37#issuecomment-31069778
     * @return \PHPUnit_Extensions_Database_Operation
     */
    protected function getSetUpOperation()
    {
        return new \PHPUnit_Extensions_Database_Operation_Composite(
            array(
                \PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL(),
                \PHPUnit_Extensions_Database_Operation_Factory::INSERT()
            ));
    }


    protected function createArrayDataSet(array $data)
    {
        return new ArrayDataSet($data);
    }


    protected function getDbAdapter()
    {
        return new Db\Adapter\Adapter($this->getDbConfig()
            ->get('adapter')
            ->toArray());
    }


    protected function getPdo()
    {
        return $this->getDbAdapter()
            ->getDriver()
            ->getConnection()
            ->getResource();
    }


    protected function getDbName()
    {
        return $this->getDbConfig()
            ->get('adapter')
            ->get('database');
    }


    protected function getDbConfig()
    {
        if (null === $this->dbConfig) {
            $this->dbConfig = new Config(require TESTS_CONFIG_DIR . 'db.cfg.php');
        }

        return $this->dbConfig;
    }


    protected function toDbDateTimeString(DateTime $dateTime)
    {
        return $dateTime->format('Y-m-d H:i:s');
    }
}