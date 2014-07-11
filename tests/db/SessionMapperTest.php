<?php

namespace InoOicServerTest\Db;

use InoOicServer\Test\TestCase\AbstractDatabaseTestCase;
use InoOicServer\Test\DbUnit\ArrayDataSet;
use InoOicServer\Oic\Session\Mapper\DbMapper;


class SessionMapperTest extends AbstractDatabaseTestCase
{

    /**
     * @var 
     */
    protected $mapper;


    public function getDataSet()
    {
        return new ArrayDataSet(array());
    }


    public function setUp()
    {
        $this->mapper = new DbMapper($this->getDbAdapter());
    }


    public function testTest()
    {
        _dump($this->getConnection());
    }
}