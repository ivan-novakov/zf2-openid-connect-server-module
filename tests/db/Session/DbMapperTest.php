<?php

namespace InoOicServerTest\Db\Session;

use InoOicServer\Test\TestCase\AbstractDatabaseTestCase;
use InoOicServer\Test\DbUnit\ArrayDataSet;
use InoOicServer\Oic\Session\Mapper\DbMapper;


class DbMapperTest extends AbstractDatabaseTestCase
{

    /**
     * @var 
     */
    protected $mapper;


    protected function getDataSet()
    {
        $dataSet = new ArrayDataSet(array(
            'auth_session' => array(
                array(
                    'id' => 'dummy_auth_session_id',
                    'method' => 'dummy_auth',
                    'create_time' => '2014-07-10 10:00:00',
                    'expiration_time' => '2014-07-10 11:00:00',
                    'user_id' => 'testuser',
                    'user_data' => 'fake_user_data'
                )
            ),
            'session' => array(
                array(
                    'id' => 'dummy_session_id',
                    'auth_session_id' => 'dummy_auth_session_id',
                    'create_time' => '2014-07-10 10:00:01',
                    'modify_time' => '2014-07-10 10:00:10',
                    'expiration_time' => '2014-07-10 11:00:10',
                    'nonce' => 'dummy_nonce'
                )
            )
        ));
        
        return $dataSet;
    }


    public function setUp()
    {
        parent::setUp();
        $this->mapper = new DbMapper($this->getDbAdapter());
    }


    public function testFetch()
    {
        $session = $this->mapper->fetch('dummy_session_id');
        $this->assertInstanceOf('InoOicServer\Oic\Session\Session', $session);
        $this->assertSame('dummy_session_id', $session->getId());
        $this->assertSame('dummy_auth_session_id', $session->getAuthSessionId());
        $this->assertEquals(new \DateTime('2014-07-10 10:00:01'), $session->getCreateTime());
    }


    public function testFetchNotFound()
    {
        $this->assertNull($this->mapper->fetch('non_existent_id'));
    }
}