<?php

namespace InoOicServerTest\Db\AuthCode;

use InoOicServer\Test\TestCase\AbstractDatabaseTestCase;
use InoOicServer\Oic\AuthCode\Mapper\DbMapper;


class DbMapperTest extends AbstractDatabaseTestCase
{

    /**
     * @var DbMapper
     */
    protected $mapper;


    public function setUp()
    {
        parent::setUp();
        $this->mapper = new DbMapper($this->getDbAdapter());
    }


    public function testSaveNewAuthCode()
    {
        $authCodeData = array(
            'code' => 'new_dummy_auth_code',
            'session_id' => 'dummy_session_id',
            'create_time' => '2014-07-10 12:00:02',
            'expiration_time' => '2014-07-10 12:05:02',
            'client_id' => 'another_dummy_client_id',
            'scope' => 'dummy scope foo'
        );

        $authCode = $this->mapper->createEntityFromData($authCodeData);
        $this->mapper->save($authCode);

        $queryTable = $this->getConnection()->createQueryTable('authorization_code', 'SELECT * FROM authorization_code');
        $expectedTable = $this->createArrayDataSet(
            array(
                'authorization_code' => array(
                    $this->getRawTableData('authorization_code', 0),
                    $authCodeData
                )
            ))
            ->getTable('authorization_code');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }


    public function testSaveExistingAuthCode()
    {
        $authCodeData = array(
            'code' => 'dummy_auth_code',
            'session_id' => 'dummy_session_id',
            'create_time' => '2014-07-10 12:00:02',
            'expiration_time' => '2014-07-10 12:05:02',
            'client_id' => 'another_dummy_client_id',
            'scope' => 'dummy scope foo'
        );

        $authCode = $this->mapper->createEntityFromData($authCodeData);
        $this->mapper->save($authCode);

        $queryTable = $this->getConnection()->createQueryTable('authorization_code', 'SELECT * FROM authorization_code');
        $expectedTable = $this->createArrayDataSet(
            array(
                'authorization_code' => array(
                    $authCodeData
                )
            ))->getTable('authorization_code');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }


    public function testFetch()
    {
        $code = 'dummy_auth_code';
        $authCode = $this->mapper->fetch($code);

        $this->assertValidAuthCode($authCode);
    }


    public function testFetchNotFound()
    {
        $this->assertNull($this->mapper->fetch('non_existent_auth_code'));
    }


    public function testFetchBySession()
    {
        $sessionId = 'dummy_session_id';
        $clientId = 'dummy_client_id';
        $authCode = $this->mapper->fetchBySession($sessionId, $clientId);

        $this->assertValidAuthCode($authCode);
    }


    public function testFetchBySessionNotFound()
    {
        $this->assertNull($this->mapper->fetchBySession('non_existent_session_id', 'dummy_client_id'));
    }

    // -----------------------
    protected function assertValidAuthCode($authCode)
    {
        /* @var $authCode \InoOicServer\Oic\AuthCode\AuthCode */
        $this->assertInstanceOf('InoOicServer\Oic\AuthCode\AuthCode', $authCode);

        $expected = $this->getRawTableData('authorization_code', 0);

        $this->assertSame($expected['code'], $authCode->getCode());
        $this->assertSame($expected['session_id'], $authCode->getSessionId());
        $this->assertSame($expected['create_time'], $this->toDbDateTimeString($authCode->getCreateTime()));
        $this->assertSame($expected['expiration_time'], $this->toDbDateTimeString($authCode->getExpirationTime()));
        $this->assertSame($expected['client_id'], $authCode->getClientId());
        $this->assertSame($expected['scope'], $authCode->getScope());
    }


    protected function createRawTableData()
    {
        return array(
            'auth_session' => array(
                array(
                    'id' => 'dummy_auth_session_id',
                    'method' => 'dummy_auth',
                    'create_time' => '2014-07-10 10:00:00',
                    'expiration_time' => '2014-07-10 11:00:00',
                    'user_id' => 'testuser',
                    'user_data' => 'fake_user_data'
                ),
                array(
                    'id' => 'dummy_new_auth_session_id',
                    'method' => 'dummy_auth',
                    'create_time' => '2014-07-11 09:00:00',
                    'expiration_time' => '2014-07-11 10:00:00',
                    'user_id' => 'otheruser',
                    'user_data' => 'other_fake_user_data'
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
            ),
            'authorization_code' => array(
                array(
                    'code' => 'dummy_auth_code',
                    'session_id' => 'dummy_session_id',
                    'create_time' => '2014-07-10 10:00:02',
                    'expiration_time' => '2014-07-10 10:05:02',
                    'client_id' => 'dummy_client_id',
                    'scope' => 'dummy scope'
                )
            )
        );
    }
}