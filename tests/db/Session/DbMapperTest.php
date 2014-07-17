<?php
namespace InoOicServerTest\Db\Session;

use InoOicServer\Test\TestCase\AbstractDatabaseTestCase;
use InoOicServer\Oic\Session\Mapper\DbMapper;

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

    public function testSaveNewSession()
    {
        $sessionData = array(
            'id' => 'new_dummy_session_id',
            'auth_session_id' => 'dummy_new_auth_session_id',
            'create_time' => '2014-07-11 09:10:00',
            'modify_time' => '2014-07-11 09:10:00',
            'expiration_time' => '2014-07-11 10:10:00',
            'nonce' => 'other_dummy_nonce'
        );

        $session = $this->mapper->getFactory()->createEntityFromData($sessionData);
        $this->mapper->save($session);

        $queryTable = $this->getConnection()->createQueryTable('session', 'SELECT * FROM session');
        $expectedTable = $this->createArrayDataSet(array(
            'session' => array(
                $this->getRawTableData('session', 0),
                $sessionData
            )
        ))
            ->getTable('session');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testSaveExistingSession()
    {
        $sessionData = array(
            'id' => 'dummy_session_id',
            'auth_session_id' => 'dummy_new_auth_session_id',
            'create_time' => '2014-07-11 09:10:00',
            'modify_time' => '2014-07-11 09:10:00',
            'expiration_time' => '2014-07-11 10:10:00',
            'nonce' => 'other_dummy_nonce'
        );

        $session = $this->mapper->getFactory()->createEntityFromData($sessionData);
        $this->mapper->save($session);

        $queryTable = $this->getConnection()->createQueryTable('session', 'SELECT * FROM session');
        $expectedTable = $this->createArrayDataSet(array(
            'session' => array(
                $sessionData
            )
        ))->getTable('session');

        $this->assertTablesEqual($expectedTable, $queryTable);
    }

    public function testFetch()
    {
        $session = $this->mapper->fetch('dummy_session_id');
        $this->assertValidSession($session);
    }

    public function testFetchNotFound()
    {
        $this->assertNull($this->mapper->fetch('non_existent_id'));
    }

    public function testFetchByCode()
    {
        $code = 'dummy_auth_code';
        $session = $this->mapper->fetchByCode($code);

        $this->assertValidSession($session);
    }

    public function testFetchByCodeNotFound()
    {
        $this->assertNull($this->mapper->fetchByCode('non_existent_code'));
    }

    public function testFetchByAccessToken()
    {
        $token = 'dummy_access_token';
        $session = $this->mapper->fetchByAccessToken($token);

        $this->assertValidSession($session);
    }

    public function testFetchByAcessTokenNotFound()
    {
        $this->assertNull($this->mapper->fetchByAccessToken('non_existent_token'));
    }

    public function testFetchByUserId()
    {
        $userId = 'testuser';
        $session = $this->mapper->fetchByUserId($userId);

        $this->assertValidSession($session);
    }

    public function testFetchByUserIdNotFound()
    {
        $this->assertNull($this->mapper->fetchByUserId('non_existent_user_id'));
    }

    public function testFetchByAuthSessionId()
    {
        $authSessionId = 'dummy_auth_session_id';
        $session = $this->mapper->fetchByAuthSessionId($authSessionId);

        $this->assertValidSession($session);
    }

    public function testFetchByAuthSessionIdNotFound()
    {
        $this->assertNull($this->mapper->fetchByAuthSessionId('non_existent_auth_session_id'));
    }

    // --------------------------------
    protected function assertValidSession($session)
    {
        /* @var $session \InoOicServer\Oic\Session\Session */
        $this->assertInstanceOf('InoOicServer\Oic\Session\Session', $session);
        $this->assertSame('dummy_session_id', $session->getId());
        $this->assertSame('dummy_auth_session_id', $session->getAuthSessionId());
        $this->assertEquals('2014-07-10 10:00:01', $this->toDbDateTimeString($session->getCreateTime()));
        $this->assertEquals('2014-07-10 10:00:10', $this->toDbDateTimeString($session->getModifyTime()));
        $this->assertEquals('2014-07-10 11:00:10', $this->toDbDateTimeString($session->getExpirationTime()));
        $this->assertSame('dummy_nonce', $session->getNonce());
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
            ),
            'access_token' => array(
                array(
                    'token' => 'dummy_access_token',
                    'session_id' => 'dummy_session_id',
                    'create_time' => '2014-07-10 10:00:03',
                    'expiration_time' => '2014-07-10 12:00:03',
                    'client_id' => 'dummy_client_id',
                    'type' => 'foo',
                    'scope' => 'dummy scope'
                )
            )
        );
    }
}